<?php

namespace App\Filament\App\Resources\RegistrationResource\Widgets;

use App\Models\Registration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrationUserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pendaftaran Diterima', Registration::where('user_id', auth()->id())->where('status', 'accepted')->count()),
            Stat::make('Acara Berjalan', Registration::where('user_id', auth()->id())->where('status', 'accepted')->whereHas('event', fn ($query) => $query->where('status', 'ongoing'))->count()),
            Stat::make('Acara Selesai', Registration::where('user_id', auth()->id())->where('status', 'present')->whereHas('event', fn ($query) => $query->where('status', 'done'))->count()),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->isUser();
    }
}
