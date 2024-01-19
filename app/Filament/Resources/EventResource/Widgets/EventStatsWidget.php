<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Acara', Event::count()),
            Stat::make('Total Acara Berjalan/Ongoing', Event::where('status', 'ongoing')->count()),
            Stat::make('Total Acara Selesai', Event::where('status', 'done')->count()),
        ];
    }
}
