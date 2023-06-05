<?php

namespace LaraZeus\Bolt\Filament\Resources;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use LaraZeus\Bolt\Filament\Resources\CollectionResource\Pages;

class CollectionResource extends BoltResource
{
    public static function getModel(): string
    {
        return config('zeus-bolt.models.Collection');
    }

    protected static ?string $navigationIcon = 'clarity-folder-open-outline-badged';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'values'];
    }

    protected static function getNavigationBadge(): ?string
    {
        return (string) config('zeus-bolt.models.Collection')::query()->count();
    }

    public static function getLabel(): string
    {
        return __('Collection');
    }

    public static function getPluralLabel(): string
    {
        return __('Collections');
    }

    protected static function getNavigationLabel(): string
    {
        return __('Collections');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label(__('Collections Name'))->required()->maxLength(255)->columnSpan(2),
                Repeater::make('values')
                    ->grid([
                        'default' => 1,
                        'md' => 2,
                        'lg' => 3,
                    ])
                    ->label(__('Collections Values'))
                    ->schema([
                        TextInput::make('itemKey')->required()->label(__('Key'))->hint(__('what store in the form')),
                        TextInput::make('itemValue')->required()->label(__('Value'))->hint(__('what the user will see')),
                        Toggle::make('itemIsDefault')->label(__('selected by default')),
                    ])->columnSpan(2)->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Collections Name'))->searchable(),
                TextColumn::make('values-list')->html()->label(__('Collections Values'))->searchable(['values']),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }
}
