<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Resources\EventResource\Widgets\EventStatsWidget;
use App\Filament\App\Resources\RegistrationResource\Widgets\LatestRegistrations;
use App\Filament\App\Resources\RegistrationResource\Widgets\RegistrationUserStatsWidget;
use App\Filament\App\Resources\RegistrationResource\Widgets\RegistrationChart;
use App\Filament\App\Resources\RegistrationResource\Widgets\RegistrationStatsWidget;
use Filament\Pages\Page;

class Stats extends Page
{
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static string $view = 'filament.app.pages.stats';
    protected function getHeaderWidgets(): array
    {
        return [
            EventStatsWidget::class,
            RegistrationUserStatsWidget::class,
            RegistrationStatsWidget::class,
            RegistrationChart::class,
            LatestRegistrations::class
        ];
    }
}
