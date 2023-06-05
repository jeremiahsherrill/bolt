<?php

namespace LaraZeus\Bolt\Filament\Resources\FormResource\Widgets;

use Filament\Widgets\PieChartWidget;
use Illuminate\Database\Eloquent\Model;
use LaraZeus\Bolt\Models\FormsStatus;
use LaraZeus\Bolt\Models\Response;

class ResponsesPerStatus extends PieChartWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = [
        'sm' => 1,
    ];

    protected static ?string $maxHeight = '300px';

    protected function getHeading(): string
    {
        return __('Responses Status');
    }

    protected function getData(): array
    {
        if ($this->record === null) {
            return [];
        }

        $statuses = FormsStatus::get();
        foreach ($statuses as $status) {
            $dataset[] = Response::query()
                ->where('form_id', $this->record->id)
                ->where('status', $status->key)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => __('entries per month'),
                    'data' => $dataset,
                    'backgroundColor' => $statuses->pluck('chartColor'),
                    'borderColor' => '#ffffff',
                ],
            ],

            'labels' => $statuses->pluck('label'),
        ];
    }
}
