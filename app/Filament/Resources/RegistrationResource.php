<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Filament\Resources\RegistrationResource\RelationManagers;
use Filament\Forms\Components\Builder as FilamentBuilder;
use Filament\Forms\Components\Card as FilamentCard;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    protected static ?string $navigationParentItem = 'Acara';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $modelLabel = 'Pendaftaran';

    public static function form(Form $form): Form
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
                    ->preload()
                    ->options(Event::where('user_id', '=', auth()->id())->pluck('title', 'id'))
                    ->hiddenOn('edit'),
                    Textarea::make('message')
                    ->label('Pesan')
                    ->maxLength(1000),
                    FileUpload::make('image')
                    ->label('File Pendukung(gambar)')
                    ->columnSpanFull()
                    ->image()
                    ->directory('events/registration')
                    ->openable()
                    ->downloadable(),
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

    public static function table(Table $table): Table
    {
        return $table
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
                Filter::make('accepted')->query(fn (Builder $query): Builder => $query->where('status', 'accepted')),
                Filter::make('rejected')->query(fn (Builder $query): Builder => $query->where('status', 'rejected')),
                Filter::make('present')->query(fn (Builder $query): Builder => $query->where('status', 'present'))
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }
}
