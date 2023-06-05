<?php

namespace LaraZeus\Bolt\Facades;

use Filament\Facades\Filament;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\Finder\Finder;

class Bolt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'bolt';
    }

    public static function availableFields()
    {
        if (app()->isLocal()) {
            Cache::forget('bolt.fields');
        }

        return Cache::remember('bolt.fields', Carbon::parse('1 month'), function () {
            $coreFields = Bolt::collectFields(__DIR__ . '/../Fields/Classes', 'LaraZeus\\Bolt\\Fields\\Classes\\');
            $appFields = Bolt::collectFields(app_path('Zeus/Fields'), 'App\\Zeus\\Fields\\');

            $fields = collect();

            if (! $coreFields->isEmpty()) {
                $fields = $fields->merge($coreFields);
            }

            if (! $appFields->isEmpty()) {
                $fields = $fields->merge($appFields);
            }

            return $fields->sortBy('sort');
        });
    }

    public static function collectFields($path, $namespace)
    {
        if (! is_dir($path)) {
            return collect();
        }
        $classes = Bolt::loadClasses($path, $namespace);
        $allFields = Bolt::setFields($classes);

        return collect($allFields);
    }

    protected static function setFields($classes)
    {
        $allFields = [];
        foreach ($classes as $class) {
            $fieldClass = new $class();
            if (! $fieldClass->disabled) {
                $allFields[] = $fieldClass->toArray();
            }
        }

        return $allFields;
    }

    public static function loadClasses($path, $namespace)
    {
        $classes = [];
        $path = array_unique(Arr::wrap($path));

        foreach ((new Finder())->in($path)->files() as $className) {
            $classes[] = $namespace . $className->getFilenameWithoutExtension();
        }

        return $classes;
    }

    public static function prepareFieldsAndSectionToRender($zeusForm, $item): array
    {
        $sections = [];
        $zeusSections = $zeusForm->sections()->orderBy('ordering')->get();
        $countSections = 0;
        foreach ($zeusSections as $section) {
            $countSections++;
            $fields = [];

            $fields[] = static::renderHook('zeus-form-section.before');

            /*if ($item !== null && $countSections === 1) {
                // todo adding title comment if there is an extension, extensions should define their own fields somehow
                $fields[] = TextInput::make('itemData.title')->label(__('Ticket Title'))->required();
            }*/

            foreach ($section->fields()->orderBy('ordering')->get() as $zeusField) {
                $fields[] = static::renderHook('zeus-form-field.before');

                $fieldClass = new $zeusField->type;
                $component = $fieldClass->renderClass::make('zeusData.' . $zeusField->id);

                $fields[] = $fieldClass->appendFilamentComponentsOptions($component, $zeusField);

                $fields[] = static::renderHook('zeus-form-field.after');
            }

            $fields[] = static::renderHook('zeus-form-section.after');

            if (optional($zeusForm->options)['show-as'] === 'tabs') {
                $sections[] = Tabs\Tab::make($section->name)
                    ->icon($section->section_icon ?? null)
                    ->schema([
                        Card::make()->columns($section->section_column)->schema($fields),
                    ]);
            } elseif (optional($zeusForm->options)['show-as'] === 'wizard') {
                $sections[] = Wizard\Step::make($section->name)
                    ->description($section->section_descriptions)
                    ->icon($section->section_icon ?? null)
                    ->schema([
                        Card::make()->columns($section->section_column)->schema($fields),
                    ]);
            } else {
                $sections[] = Section::make($section->name)
                    ->schema($fields)
                    ->aside()
                    ->aside(fn () => $section->section_aside)
                    ->description($section->section_descriptions)
                    ->columns($section->section_column);
            }
        }

        if (optional($zeusForm->options)['show-as'] === 'tabs') {
            return [Tabs::make('tabs')->tabs($sections)];
        }

        if (optional($zeusForm->options)['show-as'] === 'wizard') {
            return [Wizard::make($sections)];
        }

        return $sections;
    }

    public static function renderHook($hook)
    {
        return Placeholder::make('placeholder-' . $hook)
            ->label('')
            ->content(Filament::renderHook($hook))
            ->visible(! empty(Filament::renderHook($hook)->toHtml()));
    }

    public static function renderHookBlade($hook)
    {
        if (! empty(Filament::renderHook($hook)->toHtml())) {
            return Filament::renderHook($hook);
        }
    }
}
