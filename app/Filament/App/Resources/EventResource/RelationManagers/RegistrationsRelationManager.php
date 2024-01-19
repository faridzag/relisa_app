<?php

namespace App\Filament\App\Resources\EventResource\RelationManagers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Card as FilamentCard;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
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
                    ->maxLength(1000)
                    ->hidden(! auth()->user()->isUser()).
                    Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accept',
                        'rejected' => 'Reject',
                        'present' => 'Present',
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
                TextColumn::make('event.title')->label('Acara')
                ->sortable()
                ->searchable(),
                TextColumn::make('message')->limit(50),
                ImageColumn::make('image')->label('File Pendukung'),
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
                Tables\Actions\Action::make('Accept')->label('')->icon('heroicon-o-check')
                       ->action(fn (Registration $record) => self::accepted($record))->visible(auth()->user()->isEventManager()),
                Tables\Actions\Action::make('Reject')->label('')->icon('heroicon-o-x-mark')
                       ->action(fn (Registration $record) => self::rejected($record))->visible(auth()->user()->isEventManager()),
                Tables\Actions\Action::make('Present')->label('')->icon('heroicon-o-clock')
                       ->action(fn (Registration $record) => self::present($record))->visible(auth()->user()->isEventManager()),
            ])
            ->bulkActions([
            ]);
    }

    public static function accepted(Registration $record)
    {
        $record->status = "accepted";
        $record->save();
    }

    public static function rejected(Registration $record)
    {
        $record->status = "rejected";
        $record->save();
    }

    public static function present(Registration $record)
    {
        $record->status = "present";
        $record->save();
    }
}
