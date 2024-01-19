<?php

namespace App\Filament\App\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Acara', Event::where('user_id', auth()->id())->count()),
            Stat::make('Total Acara Berjalan/Ongoing', Event::where('user_id', auth()->id())->where('status', 'ongoing')->count()),
            Stat::make('Total Acara Selesai', Event::where('user_id', auth()->id())->where('status', 'done')->count()),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->isEventManager();
    }
}
