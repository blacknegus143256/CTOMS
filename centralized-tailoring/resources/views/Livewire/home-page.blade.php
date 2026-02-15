<div class="min-h-screen bg-gray-50 font-sans">
    
    <div class="bg-white pt-12 pb-8 text-center border-b border-gray-200 shadow-sm">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-2">
            Tailor Comparison Tool
        </h1>
        <p class="text-gray-500 text-lg">
            Filter by needs, then compare prices side-by-side.
        </p>
    </div>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <div class="lg:col-span-1 sticky top-6 self-start bg-white p-6 rounded-xl shadow-sm border border-gray-100 max-h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-lg">1. Filter List</h2>
                    
                    @if(!empty($selectedAttributes))
                        <button wire:click="$set('selectedAttributes', [])" class="text-xs text-red-500 hover:text-red-700 font-bold hover:underline">
                            Clear
                        </button>
                    @endif
                </div>

                <input 
                    wire:model.live="search" 
                    type="text" 
                    placeholder="Search name..." 
                    class="w-full mb-6 px-3 py-2 text-sm rounded border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-orange-500 outline-none transition"
                >

                <div class="space-y-8">
                    @foreach($categories as $category)
                        <div>
                            <h3 class="font-bold text-gray-900 text-xs uppercase tracking-wider mb-3">
                                {{ $category->name }}
                            </h3>
                            <div class="space-y-2">
                                @foreach($category->attributes as $attribute)
                                    <label class="flex items-center space-x-3 cursor-pointer group hover:bg-gray-50 p-1 rounded -ml-1">
                                        <input 
                                            type="checkbox" 
                                            value="{{ $attribute->id }}" 
                                            wire:model.live="selectedAttributes" 
                                            class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500"
                                        >
                                        <span class="text-gray-600 group-hover:text-orange-600 text-sm font-medium">
                                            {{ $attribute->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-3">
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                    <h2 class="font-bold text-gray-800 text-lg mb-4">2. Select Shops to Compare</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Shop A</label>
                            <select wire:model.live="shop1_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none font-bold text-gray-700">
                                <option value="">-- Select First Shop --</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Shop B</label>
                            <select wire:model.live="shop2_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none font-bold text-gray-700">
                                <option value="">-- Select Second Shop --</option>
                                @foreach($shops as $shop)
                                    @if($shop->id != $shop1_id) 
                                        <option value="{{ $shop->id }}">{{ $shop->shop_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                @if($compareShops->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="p-4 w-1/4 text-xs font-bold text-gray-400 uppercase tracking-wider">Feature</th>
                                    @foreach($compareShops as $shop)
                                        <th class="p-4 text-center w-1/3">
                                            <div class="text-xl font-bold text-gray-900">{{ $shop->shop_name }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $shop->contact_number ?? 'No Phone' }}</div>
                                            <a href="{{ route('shop.show', $shop->id) }}" class="text-orange-500 text-xs hover:underline mt-2 block">View Profile</a>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                
                                <tr>
                                    <td class="p-4 font-medium text-gray-600 bg-gray-50/50">Location</td>
                                    @foreach($compareShops as $shop)
                                        <td class="p-4 text-center text-sm text-gray-700">{{ $shop->address }}</td>
                                    @endforeach
                                </tr>

                                @foreach($categories as $category)
                                    <tr class="bg-orange-50/50">
                                        <td colspan="{{ $compareShops->count() + 1 }}" class="p-2 pl-4 text-xs font-bold text-orange-800 uppercase tracking-wide">
                                            {{ $category->name }}
                                        </td>
                                    </tr>

                                    @foreach($category->attributes as $attr)
                                        <tr>
                                            <td class="p-4 font-medium text-gray-700 border-r border-gray-50">{{ $attr->name }}</td>
                                            
                                            @foreach($compareShops as $shop)
                                                @php
                                                    $item = $shop->attributes->firstWhere('id', $attr->id);
                                                @endphp
                                                <td class="p-4 text-center">
                                                    @if($item)
                                                        <span class="block font-bold text-gray-900">₱{{ $item->pivot->price }}</span>
                                                        <span class="text-xs text-gray-500">{{ $item->pivot->unit }}</span>
                                                    @else
                                                        <span class="text-gray-300 text-2xl font-light">–</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                        <div class="text-4xl mb-4">⚖️</div>
                        <h3 class="text-lg font-bold text-gray-900">Ready to Compare</h3>
                        <p class="text-gray-500">Select two shops from the dropdowns above to see their prices side-by-side.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>