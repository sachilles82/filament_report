<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationGroup = 'Service';
    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Dienstleistung';
    protected static ?string $pluralModelLabel = 'Dienstleistungen';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Service Name')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Beschreibung')
                    ->required()
                    ->maxLength(255),
                TextInput::make('ek_price')
                    ->label('EK Preis in CHF')
                    ->rule('numeric')
                    ->required(),
                TextInput::make('vk_price')
                    ->label('VK Preis in CHF')
                    ->rule('numeric')
                    ->required(),
                Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->label('Einheit Auswahl'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Service Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Beschreibung')
                    ->visibleFrom('lg')
                    ->limit(25)
                    ->sortable()
                    ->tooltip(fn (Model $record): string => "{$record->desc}")
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->visibleFrom('sm')
                    ->sortable()
                    ->searchable()
                    ->label('Einheit'),
                TextColumn::make('ek_price')
                    ->label('EK Preis')
                    ->visibleFrom('lg')
                    ->searchable()
                    ->sortable()
                    ->money('CHF'),
                TextColumn::make('vk_price')
                    ->label('VK Preis')
                    ->searchable()
                    ->sortable()
                    ->money('CHF'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                EditAction::make()
                    ->label('')
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['vk_price'] = $data['vk_price']/100;
                        $data['ek_price'] = $data['ek_price']/100;
                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['vk_price'] = $data['vk_price']*100;
                        $data['ek_price'] = $data['ek_price']*100;
                        return $data;
                    }),
                DeleteAction::make()
                    ->label(''),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
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
            'index' => Pages\ManageItems::route('/'),
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
