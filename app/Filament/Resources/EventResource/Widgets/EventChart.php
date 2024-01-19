<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Event;
use Carbon\Carbon;

class EventChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Acara Pebulan';
    protected int | string | array $columnSpan = 'full';
    //protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '225px';
    protected static ?float $tension = 0.4;

    protected function getData(): array
    {
        $data = Trend::model(Event::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->dateColumn('published_at')
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'event/acara',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(function (TrendValue $value){
                $date = Carbon::createFromFormat('Y-m', $value->date);
                $formattedDate = $date->format('M');

                return $formattedDate;
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return auth()->user()->isAdmin();
    }
}
