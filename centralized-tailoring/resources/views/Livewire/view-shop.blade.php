<div class="max-w-4xl mx-auto p-6">
    <a href="{{ route('home') }}" class="text-gray-500 hover:text-amber-600 mb-6 inline-block font-medium">
        &larr; Back to Shops
    </a>

    <div class="bg-white rounded-xl shadow-sm p-8 mb-8 border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $shop->shop_name }}</h1>
        <div class="space-y-1">
            <p class="text-gray-600 flex items-center gap-2">
                <span>📍</span> {{ $shop->address ?? 'No address provided' }}
            </p>
            <p class="text-gray-600 flex items-center gap-2">
                <span>📞</span> {{ $shop->contact_number ?? 'No contact info' }}
            </p>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Services & Pricing</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- We use $shop->services (The relationship we built earlier) --}}
        @forelse($shop->services as $service)
            <div class="bg-white border rounded-lg p-5 flex justify-between items-start hover:shadow-md transition">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ $service->service_name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $service->description }}</p>
                </div>
                <div class="text-right ml-4">
                <div class="text-amber-600 font-bold text-lg ml-1">
                    ₱{{ number_format($service->price, 2) }}
                </div>
                <button 
                    wire:click="selectService({{ $service->id }})" 
                    class="bg-gray-900 hover:bg-black text-white text-xs py-1 px-3 rounded shadow transition"
                >
                    Book Now
                </button>
            </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-gray-50 rounded-lg text-gray-500 border border-dashed">
                <p>This shop hasn't listed any services yet.</p>
            </div>
        @endforelse
    </div>

{{-- POPUP MODAL --}}
@if($selectedService)
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 relative">
            
            <button wire:click="cancelBooking" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                ✕
            </button>

            <h3 class="text-xl font-bold text-gray-800 mb-1">Book Appointment</h3>
            <p class="text-sm text-gray-500 mb-6">
                Service: <span class="font-bold text-amber-600">{{ $selectedService->service_name }}</span> 
                (₱{{ number_format($selectedService->price, 2) }})
            </p>

            <form wire:submit.prevent="submitOrder">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                    <input wire:model="customer_name" type="text" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input wire:model="customer_phone" type="text" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea wire:model="notes" rows="2" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500" placeholder="e.g., Need it by Friday"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" wire:click="cancelBooking" class="flex-1 py-2 border rounded-lg hover:bg-gray-50 text-gray-700">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-bold shadow">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif

{{-- SUCCESS MESSAGE --}}
@if (session()->has('message'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('message') }}
    </div>
@endif
</div>