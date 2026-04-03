<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Completed Orders') }}</h1>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
                    Active Orders
                </a>
                <a href="{{ route('admin.orders.create') }}" class="admin-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Add Order
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 animate-fade-up" x-data="{ showModal: false, activeImages: [], activeIndex: 0 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="admin-panel text-card-foreground">
                <div class="admin-panel-body">

                    <form action="{{ route('admin.orders.completed') }}" method="GET" class="admin-subtle-divider">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                            <div class="space-y-2">
                                <label for="supplier_id" class="admin-label">Supplier</label>
                                <select id="supplier_id" name="supplier_id" class="admin-input">
                                    <option value="">All Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->first_name }} {{ $supplier->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="store_id" class="admin-label">Store</label>
                                <select id="store_id" name="store_id" class="admin-input">
                                    <option value="">All Stores</option>
                                    @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="customer_name" class="admin-label">Customer Name</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ request('customer_name') }}"
                                    placeholder="Search..." class="admin-input">
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" class="admin-btn-primary flex-1">
                                    Filter
                                </button>
                                <a href="{{ route('admin.orders.completed') }}" class="admin-btn-secondary">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="mb-3 text-[11px] font-bold uppercase tracking-wider text-muted-foreground/60">
                        Orders Found: <span class="text-foreground">{{ $orders->total() }}</span>
                    </div>

                    <div class="admin-table-shell">
                        <table class="admin-table">
                            <thead class="admin-table-head">
                                <tr class="admin-tr border-b-2">
                                    <th class="admin-th">Customer</th>
                                    <th class="admin-th">Product</th>
                                    <th class="admin-th">Variation</th>
                                    <th class="admin-th">Qty</th>
                                    <th class="admin-th">Note</th>
                                    <th class="admin-th">Source</th>
                                    <th class="admin-th">Fulfillment</th>
                                    <th class="admin-th">Status</th>
                                    <th class="admin-th">Date</th>
                                    <th class="admin-th">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="[&_tr:last-child]:border-0 font-medium">
                                @forelse ($orders as $order)
                                <tr class="admin-tr">
                                    @php
                                        $decodedImages = json_decode($order->image_path ?? '', true);
                                        $imagePaths = (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages))
                                            ? $decodedImages
                                            : ($order->image_path ? [$order->image_path] : []);
                                        $imagePath = $imagePaths[0] ?? null;
                                    @endphp
                                    <td class="admin-td">
                                        <div class="font-bold text-foreground text-sm">{{ $order->customer_name ?? 'N/A' }}</div>
                                    </td>

                                    <td class="admin-td">
                                        @if($imagePath)
                                        <div class="relative h-12 w-12 overflow-hidden rounded-lg ring-1 ring-border group/img">
                                            <img src="{{ asset('storage/' . $imagePath) }}"
                                                alt="Order Image"
                                                class="h-full w-full object-cover cursor-zoom-in group-hover/img:scale-110 transition-transform"
                                                @click="showModal = true; activeImages = {{ json_encode($imagePaths) }}; activeIndex = 0">
                                        </div>
                                        @else
                                        <div class="h-12 w-12 rounded-lg bg-muted flex items-center justify-center text-[10px] text-muted-foreground font-bold border border-dashed border-border/60">
                                            NO IMG
                                        </div>
                                        @endif
                                    </td>

                                    <td class="admin-td">
                                        <div class="text-xs font-bold">{{ $order->size ?? 'N/A' }}</div>
                                        <div class="text-[10px] text-muted-foreground">{{ $order->color ?? 'N/A' }}</div>
                                    </td>

                                    <td class="admin-td text-muted-foreground font-bold">
                                        {{ $order->quantity ?? '1' }}
                                    </td>

                                    <td class="admin-td">
                                        <div class="text-[10px] text-muted-foreground truncate max-w-[120px] italic">
                                            {{ $order->note ?: 'No notes provided.' }}
                                        </div>
                                    </td>

                                    <td class="admin-td">
                                        <div class="text-xs font-bold text-foreground">{{ $order->store->name ?? 'Deleted' }}</div>
                                    </td>

                                    <td class="admin-td">
                                        <div class="text-xs font-bold text-foreground">{{ $order->supplier->first_name ?? 'Deleted' }}</div>
                                    </td>

                                    <td class="admin-td">
                                        <span class="admin-badge-success font-bold text-[10px]">
                                            COMPLETED
                                        </span>
                                    </td>

                                    <td class="admin-td text-[10px] text-muted-foreground font-bold">
                                        {{ $order->order_date->format('d/m/Y') }}
                                    </td>

                                    <td class="admin-td">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="admin-btn-secondary-sm h-8 w-8 p-0" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </a>

                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmAdminAction(this, 'Delete Order', 'Are you sure you want to permanently delete this order?', 'danger');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn-danger-sm h-8 w-8 p-0" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="p-12 text-center text-muted-foreground italic">
                                        No completed orders found matching your criteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Image Modal -->
        <div x-cloak x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4 backdrop-blur-md"
             @keydown.escape.window="showModal = false"
             @click.self="showModal = false">

            <button @click="showModal = false"
                class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <div class="relative max-w-5xl w-full flex flex-col md:flex-row gap-6">
                <!-- Sidebar Thumbnails -->
                <div class="flex md:flex-col flex-row gap-3 max-h-[70vh] overflow-auto pr-2 custom-scrollbar order-2 md:order-1">
                    <template x-for="(img, idx) in activeImages" :key="idx">
                        <button type="button" @click="activeIndex = idx"
                            class="relative rounded-xl overflow-hidden focus:outline-none group/thumb flex-shrink-0"
                            :class="activeIndex === idx ? 'ring-2 ring-primary ring-offset-2 ring-offset-black' : 'opacity-40 hover:opacity-100 transition-opacity'">
                            <img :src="`{{ asset('storage') }}/` + img"
                                class="h-16 w-16 object-cover border border-white/10 group-hover/thumb:scale-110 transition-transform">
                        </button>
                    </template>
                </div>

                <!-- Main Display Area -->
                <div class="relative flex-1 flex items-center justify-center order-1 md:order-2">
                    <button type="button"
                        class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 hover:bg-white/20 p-3 text-white backdrop-blur shadow-xl transition-all z-10"
                        x-show="activeImages.length > 1"
                        @click="activeIndex = (activeIndex - 1 + activeImages.length) % activeImages.length">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div class="relative group/main">
                        <img :src="activeImages.length ? `{{ asset('storage') }}/` + activeImages[activeIndex] : ''"
                            class="max-h-[80vh] w-auto rounded-2xl shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] border border-white/10 object-contain ring-1 ring-white/20">

                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-black/60 backdrop-blur rounded-full text-[10px] font-bold text-white uppercase tracking-widest border border-white/10">
                            Image <span x-text="activeIndex + 1"></span> of <span x-text="activeImages.length"></span>
                        </div>
                    </div>

                    <button type="button"
                        class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 hover:bg-white/20 p-3 text-white backdrop-blur shadow-xl transition-all z-10"
                        x-show="activeImages.length > 1"
                        @click="activeIndex = (activeIndex + 1) % activeImages.length">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
