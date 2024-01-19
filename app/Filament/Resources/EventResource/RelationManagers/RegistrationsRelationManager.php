<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Event;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Card as FilamentCard;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FilamentCard::make('Meta')->schema([
                    Select::make('user_id')
                    ->label('Pendaftar')
                    ->searchable()
                    ->required()
                    ->options(User::where('role', 'USER')->pluck('name', 'id'))
                    ->hiddenOn('edit'),
                    Select::make('event_id')
                    ->label('Acara')
                    ->searchable()
                    ->required()
                    ->options(Event::where('user_id', '=', auth()->id())->pluck('title', 'id'))
                    ->hiddenOn('edit'),
                    Textarea::make('message')
                    ->label('Pesan')
                    ->maxLength(1000),
                    Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accept',
                        'rejected' => 'Reject',
                    ])
                    ->required(),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('pendaftar')
            ->columns([
                TextColumn::make('user.name')->label('Pendaftar')
                ->sortable()
                ->searchable(),
                TextColumn::make('message')->limit(50),
                TextColumn::make('status')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(''),
                //Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
            ]);
    }
}
