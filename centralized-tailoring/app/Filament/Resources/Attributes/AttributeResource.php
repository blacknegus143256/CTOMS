<?php

namespace App\Filament\Resources\Attributes;

use App\Filament\Resources\Attributes\Pages\CreateAttribute;
use App\Filament\Resources\Attributes\Pages\EditAttribute;
use App\Filament\Resources\Attributes\Pages\ListAttributes;
use App\Filament\Resources\Attributes\Schemas\AttributeForm;
use App\Filament\Resources\Attributes\Tables\AttributesTable;
use App\Models\Attribute;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Str;


    class AttributeResource extends Resource
    {
        protected static ?string $model = Attribute::class;
        protected static bool $isScopedToTenant = false;
        protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

        protected static ?string $recordTitleAttribute = 'Materials';
        protected static ?string $navigationLabel = 'Materials / Items'; // Renames "Attributes" to this

        public static function form(Schema $schema): Schema
        {
            return $schema->components([
                // 1. SELECT CATEGORY
                Select::make('attribute_category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('slug', Str::slug($state));
                                
                                }),
                                
                                TextInput::make('slug')
                                ->required(),
                                ]),
                                
                                // 2. ITEM NAME
                                TextInput::make('name')
                                ->label('Item Name')
                                ->required()
                                ->maxLength(255),
                                ]);
                                }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('Item')
                ->searchable()
                ->sortable()
                ->weight('bold'),

            TextColumn::make('category.name')
                ->label('Category')
                ->badge()
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('category')
                ->relationship('category', 'name'),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
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
            'index' => ListAttributes::route('/'),
            'create' => CreateAttribute::route('/create'),
            'edit' => EditAttribute::route('/{record}/edit'),
        ];
    }
}
