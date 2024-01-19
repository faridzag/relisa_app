<?php

namespace App\Filament\App\Resources\RegistrationResource\Widgets;

use App\Models\Registration;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestRegistrations extends BaseWidget
{
    protected static ?string $heading = 'Pendaftar Terbaru';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '225px';

    public function table(Table $table): Table
    {
        $userId = auth()->id();
        return $table
            ->query(Registration::query())
            ->modifyQueryUsing(function (Builder $query) use ($userId) {
                $query->whereDate('created_at', '>', now()->subDays(7)->startOfDay());

                $query->whereIn('event_id', auth()->user()->createdEvents->pluck('id'));
            })
            ->columns([
                ImageColumn::make('image')->label('File Pendukung'),
                TextColumn::make('user.name')->label('Pendaftar')
                ->sortable()
                ->searchable(),
                TextColumn::make('event.title')->label('Acara')
                ->sortable(),
                TextColumn::make('message')->limit(50),
                TextColumn::make('status')
                ->sortable(),
            ]);
    }

    public static function canView(): bool
    {
        return auth()->user()->isEventManager();
    }
}
