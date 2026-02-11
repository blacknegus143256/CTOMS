<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\TailoringShop;
use Livewire\Component;
use Livewire\Attributes\Layout; // <--- Don't forget this import!
use App\Models\Order;
use App\Models\Service;
class ViewShop extends Component
{
    public TailoringShop $shop;

    public ?Service $selectedService = null; // Stores the service they clicked
    public $customer_name = '';
    public $customer_phone = '';
    public $notes = '';

    public function mount(TailoringShop $shop)
    {
        $this->shop = $shop;
    }

    // 2. OPEN THE POPUP
    public function selectService($serviceId)
    {
        // 1. THE BOUNCER CHECK: Is the user logged in?
    if (!Auth::check()) {
        // If not, kick them to the login page
        return redirect()->route('filament.admin.auth.login'); 
    }
        // Find the service in the database
        $this->selectedService = Service::find($serviceId);
        // 3. Pre-fill their name/phone from their account!
        // (This saves them from typing it manually)
        $user = Auth::user();
        $this->customer_name = $user->name;
        $this->customer_phone = $user->email; // Or phone if you have it
    }

    // 3. CLOSE THE POPUP
    public function cancelBooking()
    {
        $this->selectedService = null;
        $this->reset(['customer_name', 'customer_phone', 'notes']);
    }

    // 4. SAVE THE ORDER (The most important part!)
    public function submitOrder()
    {
        // A. Validate the inputs
        $this->validate([
            'customer_name' => 'required|min:3',
            'customer_phone' => 'required',
        ]);

        // B. Create the Order in the Database
        Order::create([
            'tailoring_shop_id' => $this->shop->id,
            'service_id' => $this->selectedService->id,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone, // Ensure your Order table has this column!
            'status' => 'pending', // Default status
            'total_price' => $this->selectedService->price,
            'notes' => $this->notes,
        ]);

        // C. Show success message and close popup
        session()->flash('message', 'Booking request sent successfully!');
        $this->cancelBooking();
    }
    
    #[Layout('components.layouts.app')] // <--- The "Sticky Note" Fix
    public function render()
    {
        return view('livewire.view-shop');
    }
}