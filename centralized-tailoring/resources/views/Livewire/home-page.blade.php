<div class="max-w-6xl mx-auto p-6">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Find a Tailor in Dumaguete</h1>
        <p class="text-gray-600">The centralized platform for all your tailoring needs.</p>
        
        <div class="mt-6">
            <input 
                wire:model.live="search" 
                type="text" 
                placeholder="Search for a shop name..." 
                class="w-full max-w-md px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-amber-500 outline-none"
            >
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($shops as $shop)
            <div class="bg-white border rounded-xl shadow-sm hover:shadow-md transition p-6">
                
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ $shop->shop_name }}</h2>
                    @if($shop->is_approved ?? false)
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Verified</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">New</span>
                    @endif
                </div>

                <p class="text-gray-600 text-sm mb-2">
                    <strong>📍 Location:</strong> {{ $shop->address ?? 'No address provided' }}
                </p>
                <p class="text-gray-600 text-sm mb-4">
                    <strong>📞 Contact:</strong> {{ $shop->contact_number ?? 'N/A' }}
                </p>

                <a href="{{ route('shop.show', $shop) }}" class="block text-center w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded transition">
                    View Services
                </a>
            </div>
        @endforeach
    </div>

    @if($shops->isEmpty())
        <div class="text-center text-gray-500 py-12">
            No shops found matching "{{ $search }}".
        </div>
    @endif
</div>