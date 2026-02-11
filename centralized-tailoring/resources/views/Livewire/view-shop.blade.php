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
                <div class="text-amber-600 font-bold text-lg whitespace-nowrap ml-4">
                    ₱{{ number_format($service->price, 2) }}
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-gray-50 rounded-lg text-gray-500 border border-dashed">
                <p>This shop hasn't listed any services yet.</p>
            </div>
        @endforelse
    </div>
</div>