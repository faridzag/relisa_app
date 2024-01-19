<?php

namespace App\Filament\App\Resources\RegistrationResource\Widgets;

use App\Models\Registration;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendaftar';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '225px';
    protected static ?float $tension = 0.4;

    protected function getData(): array
    {
        $data = Trend::query(Registration::query()
            ->whereHas('event', fn ($query) => $query->where('user_id', auth()->id()))
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->dateColumn('created_at')
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'pendaftar',
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
        return auth()->user()->isEventManager();
    }
}
