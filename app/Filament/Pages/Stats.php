<?php

namespace App\Filament\Pages;

use App\Filament\Resources\EventResource\Widgets\EventChart;
use App\Filament\Resources\EventResource\Widgets\EventStatsWidget;
use App\Filament\Resources\EventResource\Widgets\LatestEvents;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidget;
use Filament\Pages\Page;

class Stats extends Page
{
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static string $view = 'filament.pages.stats';
    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsWidget::class,
            EventStatsWidget::class,
            EventChart::class,
            LatestEvents::class,
        ];
    }
}
