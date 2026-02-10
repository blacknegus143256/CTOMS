<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Filament\Resources\Services\Schemas\ServiceForm;
use App\Filament\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    // protected static ?string $recordTitleAttribute = 'Services';
    protected static ?string $navigationLabel = 'Services';
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. SELECT THE SHOP (Crucial for a Centralized System)
                Select::make('tailoring_shop_id')
                    ->relationship('tailoringShop', 'shop_name') // Display the Shop Name
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Shop'),

                // 2. SERVICE DETAILS
                TextInput::make('service_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Hemming, Custom Suit'),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₱') // Adds the Peso sign
                    ->label('Base Price'),

                TextInput::make('duration_days')
                    ->label('Avg. Duration')
                    ->placeholder('e.g. 3 Days'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('service_name')->searchable()->sortable(),
                TextColumn::make('tailoringShop.shop_name')->label('Shop')->sortable(),
                TextColumn::make('price')->money('PHP')->sortable(),
                TextColumn::make('duration_days')->label('Duration'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Service $record) =>
                        static::getUrl('edit', ['record' => $record])
                    ),
            ])
            ->bulkActions([
                BulkAction::make('delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn ($records) => $records->each->delete()),
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
