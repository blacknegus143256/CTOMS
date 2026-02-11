<?php

namespace App\Livewire;

use App\Models\TailoringShop;
use Livewire\Component;
use Livewire\Attributes\Layout;

class HomePage extends Component
{
    // 1. Define the search variable
    public $search = '';

    // 2. THIS ATTRIBUTE FIXES THE LAYOUT CONNECTION
    #[Layout('components.layouts.app')] 
    public function render()
    {
        return view('livewire.home-page', [
            'shops' => TailoringShop::query()
                ->where('shop_name', 'like', "%{$this->search}%")
                ->get(),
        ]); // <--- 3. Clean finish (No ->layout here)
    }
}