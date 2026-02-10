<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages;
use App\Models\Order;
use App\Models\Service; // Needed for Auto-Price
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // Needed for filtering

// --- 1. FORM COMPONENTS ---
use Filament\Forms\Components\Actions\Concerns\CanReadState as Get;
use Filament\Forms\Components\Actions\Concerns\CanSetState as Set;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
// --- 2. TABLE COMPONENTS ---
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingBag;

    protected static ?string $navigationLabel = 'Orders';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. SELECT SHOP (Triggers the Chain Reaction)
                Select::make('tailoring_shop_id')
                    ->relationship('tailoringShop', 'shop_name')
                    ->label('Shop')
                    ->live() // <--- MAGICAL: Refreshes the form when you pick a shop
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Reset other fields if shop changes
                        $set('customer_id', null);
                        $set('service_id', null);
                    })
                    ->required(),

                // 2. SELECT CUSTOMER (Filtered by Shop)
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name', function (Builder $query, callable $get) {
                        // FILTER: Only show customers from the selected shop
                        $shopId = $get('tailoring_shop_id');
                        if ($shopId) {
                            $query->where('tailoring_shop_id', $shopId);
                        }
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn (callable $get) => ! $get('tailoring_shop_id')), // Locked until Shop is picked

                // 3. SELECT SERVICE (Auto-Fills Price)
                Select::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'service_name', function (Builder $query, callable $get) {
                        // FILTER: Only show services from the selected shop
                        $shopId = $get('tailoring_shop_id');
                        if ($shopId) {
                            $query->where('tailoring_shop_id', $shopId);
                        }
                    })
                    ->live() // <--- MAGICAL: Watch for changes
                    ->afterStateUpdated(function ($state, callable $set) {
                        // LOGIC: Find the service price and auto-fill it
                        $service = Service::find($state);
                        if ($service) {
                            $set('total_price', $service->price);
                        }
                    })
                    ->required()
                    ->disabled(fn (callable $get) => ! $get('tailoring_shop_id')),

                // 4. ORDER DETAILS
                TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->prefix('₱')
                    ->required(),

                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Measuring' => 'Measuring',
                        'Processing' => 'Processing', // Sewing
                        'Ready' => 'Ready for Pickup',
                        'Completed' => 'Completed',
                    ])
                    ->default('Pending')
                    ->required(),

                DatePicker::make('expected_completion_date')
                    ->label('Due Date')
                    ->native(false), // Uses a nice calendar popup

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('Order ID'),
                
                TextColumn::make('tailoringShop.shop_name')
                    ->label('Shop')
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service.service_name')
                    ->label('Service'),

                TextColumn::make('status')
                    ->badge() // Makes it look like a colorful tag
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'Measuring' => 'warning',
                        'Processing' => 'info',
                        'Ready' => 'success',
                        'Completed' => 'success',
                    }),

                TextColumn::make('total_price')
                    ->money('PHP'),

                TextColumn::make('expected_completion_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tailoringShop')
                    ->relationship('tailoringShop', 'shop_name'),
                
                SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Ready' => 'Ready',
                        'Completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Order $record) => static::getUrl('edit', ['record' => $record])),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}