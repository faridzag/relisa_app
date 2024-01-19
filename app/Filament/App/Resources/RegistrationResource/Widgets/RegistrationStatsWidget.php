<?php

namespace App\Filament\App\Resources\RegistrationResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendaftar', Registration::whereHas('event', fn ($query) => $query->where('user_id', auth()->id()))->count()),
            Stat::make('Pendaftar Diterima', Registration::whereHas('event', fn ($query) => $query->where('user_id', auth()->id()))->where('status', 'accepted')->count())
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->isEventManager();
    }
}
