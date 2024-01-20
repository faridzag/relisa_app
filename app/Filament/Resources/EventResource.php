<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Filament\Resources\EventResource\RelationManagers\RegistrationsRelationManager;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card as FilamentCard;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Acara';
    protected static ?string $recordTitleAttribute = 'title';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FilamentCard::make('Main')->schema([
                    TextInput::make('title')
                    ->columnSpan(2)
                    ->label('Nama Acara')
                    ->required()
                    ->maxLength(150)
                    ->live()
                    ->unique(ignoreRecord: true)
                    ->afterStateUpdated(function (string $operation, Set $set, ?string $state){
                        if ($operation === 'edit') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    }),
                    TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                    Select::make('categories')
                    ->columnSpan(1)
                    ->label('Kategori')
                    ->relationship(name: 'categories', titleAttribute: 'title')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                    RichEditor::make('description')
                    ->label('Deskripsi')
                    ->fileAttachmentsDirectory('events/images')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                ])->columns(3),
                FilamentCard::make('Meta')->schema([
                    FileUpload::make('image')
                    ->label('Thumbnail')
                    ->columnSpanFull()
                    ->image()
                    ->directory('events/thumbnails')
                    ->openable()
                    ->downloadable(),
                    DateTimePicker::make('start_date')
                    ->label('Tgl_dimulai')
                    ->timezone('Asia/Jakarta')
                    ->minDate(now())
                    ->required(),
                    TextInput::make('location')
                    ->label('Lokasi/Tempat')
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),
                    Select::make('status')
                    ->label('Status')
                    ->options([
                        'closed' => 'Closed',
                        'open' => 'Open',
                        'ongoing' => 'Ongoing',
                        'done' => 'Done',
                    ])
                    ->required()
                    ->live(),
                    DateTimePicker::make('published_at')
                    ->label('Tgl_publish')
                    ->minDate(today())
                    ->timezone('Asia/Jakarta')
                    ->hidden(fn (Get $get) => $get('status') !== 'open'),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Thumbnail')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author.name')->label('Event-Manager')
                ->sortable()
                ->searchable(),
                TextColumn::make('title')->label('Nama Acara')
                    ->words(4)
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('categories.title')->label('Kategori')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('start_date')->label('Tgl_dimulai')
                    ->dateTime('d-M-Y H:m:s')
                    ->sortable(),
                TextColumn::make('location')->label('Lokasi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Info Lanjut')
                    ->words(2)
                    ->html(),
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
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('open')->query(fn (Builder $query): Builder => $query->where('status', 'open')),
                Filter::make('ongoing')->query(fn (Builder $query): Builder => $query->where('status', 'ongoing')),
                Filter::make('done')->query(fn (Builder $query): Builder => $query->where('status', 'done')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()->label(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RegistrationsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
