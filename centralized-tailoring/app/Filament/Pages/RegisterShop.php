<?php

namespace App\Filament\Pages;

// --- 1. THE IMPORTS (The Toolbox) ---
use App\Models\TailoringShop; // We need this to talk to the Shop database table
use Filament\Forms\Components\TextInput; // The tool to make a text box
use Filament\Schemas\Schema;    // The tool to build the form structure
use Filament\Pages\Tenancy\RegisterTenant; // The specialized "Template" we are using
use Illuminate\Database\Eloquent\Model; // Generic tool to understand "Database Entries"
use Illuminate\Support\Facades\Auth;
// --- 2. THE CLASS ---
// We extend "RegisterTenant", NOT "Page".
// This gives us special superpowers to handle shop creation.
class RegisterShop extends RegisterTenant
{
    protected static ?string $slug = 'create-shop';
    // --- 3. THE FORM LABEL ---
    public static function getLabel(): string
    {
        return 'Register Your Shop';
    }

    // --- 4. THE BLUEPRINT (What the user sees) ---
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('shop_name')
                    ->label('Shop Name')
                    ->placeholder('e.g., Romy\'s Tailoring')
                    ->required(),
                    TextInput::make('contact_person')
                ->label('Owner/Contact Person')
                ->required(),

                TextInput::make('contact_number')
                    ->label('Phone Number')
                    ->required(),

                TextInput::make('address')
                    ->label('Shop Address')
                    ->required(),
            ]);
    }

    // --- 5. THE ACTION (What happens after submit) ---
    protected function handleRegistration(array $data): Model
    {
        // A. Get the user who is signing up
        $user = Auth::user();

        // B. Create the shop in the database
        // We use the 'TailoringShop' model to talk to the 'tailoring_shops' table
        $shop = TailoringShop::create($data);

        // C. (Optional) Auto-approve for testing so you don't get locked out
        $shop->is_approved = true; 
        $shop->save();

        // D. THE CRITICAL STEP: Link the User to the Shop
        // This fills the 'shop_user' pivot table.
        $shop->members()->attach($user);

        // E. Return the new shop so Filament knows where to redirect us
        return $shop;
    }
}