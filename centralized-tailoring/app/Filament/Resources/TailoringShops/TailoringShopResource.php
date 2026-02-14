<?php
namespace App\Filament\Resources\TailoringShops;
    
    use App\Filament\Resources\TailoringShops\Pages;
    use App\Models\TailoringShop;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Table;
    use BackedEnum;
    use Filament\Support\Icons\Heroicon;

    use Filament\Schemas\Schema;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\TextInput;

    use Filament\Actions\Action;
    use Filament\Actions\BulkAction;
    
    use App\Filament\Resources\TailoringShops\RelationManagers\AttributesRelationManager; // Make sure to import the RelationManagers namespace
    class TailoringShopResource extends Resource
    {
        protected static ?string $model = TailoringShop::class;

        // I removed the explicit type hint here to stop the "BackedEnum" error.
        // It will inherit the correct type from the parent class automatically.
        protected static string | BackedEnum | null $navigationIcon = Heroicon::BuildingOffice;

        protected static ?string $navigationLabel = 'Tailoring Shops';

        public static function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    TextInput::make('shop_name')
                        ->label('Shop Name')
                        ->required()
                        ->maxLength(255),
                    
                    TextInput::make('contact_person')
                        ->label('Contact Person')
                        ->required()
                        ->maxLength(255),
                    
                    Select::make('contact_role')
                        ->label('Role')
                        ->options([
                            'Owner' => 'Owner',
                            'Manager' => 'Manager',
                            'Staff' => 'Staff',
                        ])
                        ->required()
                        ->default('Owner'),
                    
                    Textarea::make('address')
                        ->required()
                        ->columnSpanFull(),
                    
                    TextInput::make('contact_number')
                        ->tel()
                        ->maxLength(255),
                ]);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('shop_name')
                        ->label('Shop Name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('contact_person')
                        ->label('Contact')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('contact_role')
                        ->label('Role'),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->toggleable(isToggledHiddenByDefault: true),
                ])
                ->filters([
                    //
                ])
                ->actions([
                        Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (TailoringShop $record) =>
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
            AttributesRelationManager::class,
            ];
        }
        
        public static function getPages(): array
        {
            return [
                'index' => Pages\ListTailoringShops::route('/'),
                'create' => Pages\CreateTailoringShop::route('/create'),
                'edit' => Pages\EditTailoringShop::route('/{record}/edit'),
            ];
        }
    }