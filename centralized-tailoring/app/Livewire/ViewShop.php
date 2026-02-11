<?php

namespace App\Livewire;

use App\Models\TailoringShop;
use Livewire\Component;
use Livewire\Attributes\Layout; // <--- Don't forget this import!

class ViewShop extends Component
{
    public TailoringShop $shop;

    public function mount(TailoringShop $shop)
    {
        $this->shop = $shop;
    }

    #[Layout('components.layouts.app')] // <--- The "Sticky Note" Fix
    public function render()
    {
        return view('livewire.view-shop');
    }
}