<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Filament\Resources\CustomerResource\Pages;
use Filament\Forms\Components\KeyValue; // <--- The Magic Component
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Support\Icons\Heroicon;
class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    // protected static ?string $recordTitleAttribute = 'Customer';
    protected static ?string $navigationLabel = 'Customers';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // SECTION 1: BASIC INFO
                Section::make('Customer Details')
                    ->description('Who is this customer?')
                    ->components([
                        Select::make('tailoring_shop_id')
                            ->relationship('tailoringShop', 'shop_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Shop'),

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('address')
                            ->columnSpanFull(),
                    ]),

                // SECTION 2: MEASUREMENTS (THE JSON FIELD)
                Section::make('Body Measurements')
                    ->description('Add specific measurements for this customer.')
                    ->components([
                        KeyValue::make('measurements')
                            ->keyLabel('Body Part (e.g. Waist)')
                            ->valueLabel('Size (e.g. 32 inches)')
                            ->columnSpanFull(), 
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('phone_number')
                    ->icon('heroicon-m-phone'),

                TextColumn::make('tailoringShop.shop_name')
                    ->label('Shop')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter customers by shop (just like we did for Services!)
                Tables\Filters\SelectFilter::make('tailoringShop')
                    ->relationship('tailoringShop', 'shop_name')
                    ->label('Filter by Shop'),
            ])
            ->actions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Customer $record) =>
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
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
