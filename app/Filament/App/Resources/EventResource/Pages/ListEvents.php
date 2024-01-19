<?php

namespace App\Filament\App\Resources\EventResource\Pages;

use App\Filament\App\Resources\EventResource;
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
}
