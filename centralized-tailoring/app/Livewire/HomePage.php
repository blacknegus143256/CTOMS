<?php

namespace App\Livewire;

use App\Models\TailoringShop;
use App\Models\AttributeCategory;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;  

class HomePage extends Component
{
    // 1. Define the search variable
    public $search = '';
    public $selectedAttributes = []; // Stores the checkboxes (e.g., Silk, Hemming)
    
    public $shop1_id = null;
    public $shop2_id = null;

    // 2. THIS ATTRIBUTE FIXES THE LAYOUT CONNECTION
    #[Layout('components.layouts.app')] 
    public function render()
    {
        $shopsQuery = TailoringShop::query();

        if ($this->search) {
            $shopsQuery->where('shop_name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedAttributes)) {
            $shopsQuery->whereHas('attributes', function (Builder $query) {
                $query->whereIn('attributes.id', $this->selectedAttributes);
            }); 
            // The count() ensures the shop has ALL selected items, not just one.
        }
        $filteredShops = $shopsQuery->get();

        // 2. COMPARISON LOGIC: Fetch the details for the 2 selected shops
        $compareShops = collect();

        if ($this->shop1_id || $this->shop2_id) {
            $compareShops = TailoringShop::with(['attributes' => function($q) {
                $q->withPivot('price', 'unit', 'notes');
            }])
            ->whereIn('id', array_filter([$this->shop1_id, $this->shop2_id]))
            ->get();
        }
        return view('livewire.home-page', [
            'shops' => $shopsQuery->get(),
            // Pass categories to the view so we can make the Sidebar
            'categories' => AttributeCategory::with('attributes')->get(),
            'compareShops' => $compareShops,
        ]);

        return view('livewire.home-page', [
            'shops' => TailoringShop::query()
                ->where('shop_name', 'like', "%{$this->search}%")
                ->get(),
        ]); // <--- 3. Clean finish (No ->layout here)
    }
}