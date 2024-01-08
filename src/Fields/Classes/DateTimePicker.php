<?php

namespace LaraZeus\Bolt\Fields\Classes;

use LaraZeus\Bolt\Fields\FieldsContract;

class DateTimePicker extends FieldsContract
{
    public string $renderClass = \Filament\Forms\Components\DateTimePicker::class;

    public int $sort = 5;

    public function title(): string
    {
        return __('Date Time Picker');
    }

    public static function getOptions(): array
    {
        return [
            self::htmlID(),
            self::hintOptions(),
            self::required(),
            self::columnSpanFull(),
            self::visibility(),
        ];
    }

    public static function getOptionsHidden(): array
    {
        return [
            self::hiddenHtmlID(),
            self::hiddenHintOptions(),
            self::hiddenRequired(),
            self::hiddenColumnSpanFull(),
            self::hiddenVisibility(),
        ];
    }
}
