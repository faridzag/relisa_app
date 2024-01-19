<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\EventResource\Widgets\EventChart;
use App\Filament\Resources\EventResource\Widgets\EventStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
            ->exports([
                ExcelExport::make()
                ->fromTable()
                ->withFilename(fn ($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                ->withWriterType(\Maatwebsite\Excel\Excel::CSV)
                ->withColumns([
                    Column::make('updated_at'),
                ])
                ->except(['image', 'slug', 'deleted_at', 'pesan'])
            ]),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            EventStatsWidget::class,
        ];
    }
    protected function getFooterWidgets(): array
    {
        return [
            EventChart::class,
        ];
    }
}
