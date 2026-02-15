<?php

namespace App\Filament\Resources\AttributeCategories;

use App\Filament\Resources\AttributeCategories\Pages\CreateAttributeCategory;
use App\Filament\Resources\AttributeCategories\Pages\EditAttributeCategory;
use App\Filament\Resources\AttributeCategories\Pages\ListAttributeCategories;
use App\Filament\Resources\AttributeCategories\Schemas\AttributeCategoryForm;
use App\Filament\Resources\AttributeCategories\Tables\AttributeCategoriesTable;
use App\Models\AttributeCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Str;


class AttributeCategoryResource extends Resource
{
    protected static ?string $model = AttributeCategory::class;
    protected static bool $isScopedToTenant = false;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBoxArrowDown;

    protected static ?string $recordTitleAttribute = 'Materials Category';
    protected static ?string $navigationLabel = 'Materials Categories'; // Renames "Attribute Categories" to this

    public static function form(Schema $schema): Schema
    {
    return $schema->components([
        TextInput::make('name')
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(function ($state, callable $set) {
                $set('slug', Str::slug($state));
            }),

        TextInput::make('slug')
            ->required()
            ->disabled()
            ->dehydrated()
            ->unique(ignoreRecord: true),
    ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name')
                    ->sortable()
                    ->searchable(),
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
            'index' => ListAttributeCategories::route('/'),
            'create' => CreateAttributeCategory::route('/create'),
            'edit' => EditAttributeCategory::route('/{record}/edit'),
        ];
    }
}
