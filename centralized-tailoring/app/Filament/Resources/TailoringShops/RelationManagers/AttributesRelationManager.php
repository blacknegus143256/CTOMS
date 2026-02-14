<?php

namespace App\Filament\Resources\TailoringShops\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema; // Matching your resource
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Actions\AttachAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use App\Models\Attribute; // Assuming this is the related model

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    protected static ?string $recordTitleAttribute = 'name';

    // CHANGED: Using Schema type hint to match your ServiceResource
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. SELECT ITEM
                Select::make('attribute_id')
                    ->relationship('attribute', 'name')
                    ->label('Item Name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                // 2. PRICE
                TextInput::make('price')
                    ->numeric()
                    ->prefix('₱')
                    ->required()
                    ->label('Price'),

                // 3. UNIT
                Select::make('unit')
                    ->options([
                        'per yard' => 'Per Yard',
                        'per piece' => 'Per Piece',
                        'starting at' => 'Starting Price',
                        'per set' => 'Per Set',
                    ])
                    ->default('per item')
                    ->required(),

                // 4. NOTES
                TextInput::make('notes')
                    ->label('Notes')
                    ->columnSpanFull(),
                    
                // 5. STOCK
                Toggle::make('is_available')
                    ->label('In Stock')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Item')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('price')
                    ->money('PHP')
                    ->label('Price'),

                TextColumn::make('unit')
                    ->label('Unit'),
                
                IconColumn::make('is_available')
                    ->boolean()
                    ->label('Stock'),
            ])
            ->headerActions([
                // The Attach Button
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('price')->numeric()->prefix('₱')->required(),
                        Select::make('unit')
                            ->options([
                                'per yard' => 'Per Yard',
                                'per piece' => 'Per Piece',
                                'starting at' => 'Starting Price',
                            ])->required()->default('per item'),
                        TextInput::make('notes'),
                        Toggle::make('is_available')->default(true),
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DetachAction::make(),
            ]);
    }
}