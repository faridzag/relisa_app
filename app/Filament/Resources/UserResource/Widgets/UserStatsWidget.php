<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total User', User::count()),
            Stat::make('Total Admin', User::where('role', User::ROLE_ADMIN)->count()),
            Stat::make('Total Event Manager', User::where('role', User::ROLE_EVENTMANAGER)->count()),
        ];
    }
}