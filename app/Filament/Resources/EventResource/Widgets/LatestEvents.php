<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestEvents extends BaseWidget
{
    protected static ?string $heading = 'Event Terbaru';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '225px';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::whereDate('created_at', '>', now()->subDays(4)->startOfDay())
            )
            ->columns([
                ImageColumn::make('image')->label('Thumbnail')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author.name')->label('Event-Manager')
                ->sortable(),
                TextColumn::make('title')->label('Nama Acara')
                    ->words(4)
                    ->sortable(),
                TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('categories.title')->label('Kategori')
                    ->sortable(),
                TextColumn::make('start_date')->label('Tgl_dimulai')
                    ->dateTime('d-M-Y H:m:s')
                    ->sortable(),
                TextColumn::make('location')->label('Lokasi')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->sortable(),
                TextColumn::make('published_at')->label('Tgl_publish')
                    ->dateTime('d-M-Y H:m:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
