<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\RegistrationResource\Pages;
use App\Filament\App\Resources\RegistrationResource\RelationManagers;
use App\Models\Event;
use App\Models\User;
use Filament\Forms\Components\Card as FilamentCard;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

    //protected static ?string $navigationParentItem = 'Acara Saya';
    //protected static ?string $navigationGroup = 'Acara Relawan';
    protected static ?int $navigationSort = 3;
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
                    ->relationship(name: 'event', titleAttribute: 'title',)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->hiddenOn('edit'),
                    Textarea::make('message')
                    ->label('Deskripsi')
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
                    ->visible(auth()->user()->isEventManager())
                    ->required(),
                ]),
            ]);
    }


    public static function table(Table $table): Table
    {
        $currentUser = auth()->id();
        return $table
            ->modifyQueryUsing(function (Builder $query) use ($currentUser) {
                $user = auth()->user();
                if ($user->isEventManager()) {
                    if ($user->createdEvents) {
                        $query->whereIn('event_id', $user->createdEvents->pluck('id'));
                    } else {
                        return $query->where('user_id', 0)->limit(0);
                    }
                } else {
                    $query->where('user_id', $currentUser);
                }
            })
            ->columns([
                TextColumn::make('user.name')->label('Pendaftar')
                ->sortable()
                ->searchable(),
                TextColumn::make('event.title')->label('Acara')
                ->sortable()
                ->searchable(),
                TextColumn::make('message')
                ->label('deskripsi')->words(4),
                ImageColumn::make('image')
                ->label('File Pendukung'),
                TextColumn::make('status')
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                Filter::make('accepted')->query(fn (Builder $query): Builder => $query->where('status', 'accepted')),
                Filter::make('rejected')->query(fn (Builder $query): Builder => $query->where('status', 'rejected')),
                Filter::make('present')->query(fn (Builder $query): Builder => $query->where('status', 'present'))
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
                Tables\Actions\Action::make('Accept')->label('')->icon('heroicon-o-check')
                       ->action(fn (Registration $record) => self::accepted($record))->visible(auth()->user()->isEventManager()),
                Tables\Actions\Action::make('Reject')->label('')->icon('heroicon-o-x-mark')
                       ->action(fn (Registration $record) => self::rejected($record))->visible(auth()->user()->isEventManager()),
                Tables\Actions\Action::make('Present')->label('')->icon('heroicon-o-clock')
                       ->action(fn (Registration $record) => self::present($record))->visible(auth()->user()->isEventManager()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Info Acara')
                ->schema([
                    TextEntry::make('event.title')->label('Acara')->columnSpan(2),
                    TextEntry::make('status')->columnSpan(1),
                    TextEntry::make('event.pesan')->label('Info Lanjut')->html()
                    ->columnSpanFull()
                    ->visible(fn (Registration $record) => $record->status === 'accepted' || $record->status === 'present')
                ])->columns(3),
                Section::make('Info Pendaftar')
                ->schema([
                    TextEntry::make('user.name')->label('Pendaftar'),
                    TextEntry::make('message')->label('Deskripsi Pendaftar'),
                    ImageEntry::make('image')->label('File Pendukung'),
                ])
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
            //'view' => Pages\ViewRegistration::route('/{record}'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }
}
