<x-app-layout>

    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">
                {{ __('Admin Dashboard') }}
            </h1>
            <a href="{{ route('admin.orders.completed') }}"
               class="admin-btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Completed Orders
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ showModal: false, activeImages: [], activeIndex: 0 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ══════ STAT CARDS ══════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

                <div class="admin-stat-card animate-fade-up" style="animation-delay:0ms">
                    <div class="p-5 flex items-center gap-4">
                        <div class="admin-stat-icon bg-gradient-to-br from-blue-500 to-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-muted-foreground">Total Orders</p>
                            <p class="text-2xl font-bold tracking-tight">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:50ms">
                    <div class="p-5 flex items-center gap-4">
                        <div class="admin-stat-icon bg-gradient-to-br from-amber-500 to-orange-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-muted-foreground">Not Shipped</p>
                            <p class="text-2xl font-bold tracking-tight text-destructive">{{ $notShippedOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:100ms">
                    <div class="p-5 flex items-center gap-4">
                        <div class="admin-stat-icon bg-gradient-to-br from-red-500 to-rose-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-muted-foreground">Extra Time</p>
                            <p class="text-2xl font-bold tracking-tight text-destructive">{{ $extraTimeOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:150ms">
                    <div class="p-5 flex items-center gap-4">
                        <div class="admin-stat-icon bg-gradient-to-br from-emerald-500 to-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-muted-foreground">Completed</p>
                            <p class="text-2xl font-bold tracking-tight text-green-600 dark:text-green-400">{{ $completedOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:200ms">
                    <div class="p-5 flex items-center gap-4">
                        <div class="admin-stat-icon bg-gradient-to-br from-violet-500 to-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-muted-foreground">Suppliers</p>
                            <p class="text-2xl font-bold tracking-tight">{{ $totalSuppliers }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════ FILTER & TABLE ══════ --}}
            <div class="admin-panel animate-fade-up" style="animation-delay:250ms">
                <div class="admin-panel-body">
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="admin-subtle-divider">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

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
                                <label for="sort" class="admin-label">Sort by Date</label>
                                <select name="sort_retard" class="admin-input">
                                    <option value="">Default Priority</option>
                                    <option value="most_retarded" {{ request('sort_retard') == 'most_retarded' ? 'selected' : '' }}>
                                        Orders 9dam
                                    </option>
                                    <option value="least_retarded" {{ request('sort_retard') == 'least_retarded' ? 'selected' : '' }}>
                                        Orders jdad
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="customer_name" class="admin-label">Customer Name</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ request('customer_name') }}"
                                    placeholder="Search customer..."
                                    class="admin-input">
                            </div>

                            <div class="space-y-2">
                                <label for="status" class="admin-label">Status</label>
                                <select id="status" name="status" class="admin-input">
                                    <option value="">All Statuses</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="main_time" {{ request('status') == 'main_time' ? 'selected' : '' }}>Opened Orders</option>
                                    <option value="extra_time" {{ request('status') == 'extra_time' ? 'selected' : '' }}>Extended Orders</option>
                                    <option value="not_shipped" {{ request('status') == 'not_shipped' ? 'selected' : '' }}>Not Shipped</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit" class="admin-btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" /></svg>
                                    Filter
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-6 pt-0">
                    <div class="mb-3 flex items-center gap-2 text-sm text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        <span>{{ $orders->total() }} orders</span>
                    </div>
                    <div class="admin-table-shell">
                        <table class="admin-table">
                            <thead class="admin-table-head">
                                <tr class="admin-tr">
                                    <th class="admin-th">Customer</th>
                                    <th class="admin-th">Image</th>
                                    <th class="admin-th">Details</th>
                                    <th class="admin-th">Qty</th>
                                    <th class="admin-th">Note</th>
                                    <th class="admin-th">Store</th>
                                    <th class="admin-th">Status</th>
                                    <th class="admin-th">Supplier</th>
                                    <th class="admin-th">Opened</th>
                                    <th class="admin-th">Extended</th>
                                    <th class="admin-th">Late</th>
                                    <th class="admin-th">Date</th>
                                    <th class="admin-th">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($orders as $order)
                                <tr class="admin-tr">
                                    @php
                                        $decodedImages = json_decode($order->image_path ?? '', true);
                                        $imagePaths = (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages))
                                            ? $decodedImages
                                            : ($order->image_path ? [$order->image_path] : []);
                                        $imagePath = $imagePaths[0] ?? null;
                                    @endphp

                                    <td class="admin-td font-medium">
                                        {{ $order->customer_name ?? 'N/A' }}
                                    </td>

                                    <td class="admin-td">
                                        @if($imagePath)
                                        <img src="{{ asset('storage/' . $imagePath) }}"
                                            alt="Order Image"
                                            class="h-10 w-10 rounded-lg object-cover ring-1 ring-border cursor-zoom-in hover:ring-primary/50 transition-all"
                                            @click="showModal = true; activeImages = {{ json_encode($imagePaths) }}; activeIndex = 0">
                                        @else
                                        <div class="h-10 w-10 rounded-lg bg-muted/50 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-muted-foreground/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 0 0 2.25-2.25V5.25a2.25 2.25 0 0 0-2.25-2.25H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                        </div>
                                        @endif
                                    </td>

                                    <td class="admin-td">
                                        <div class="font-medium text-sm">{{ $order->size ?? 'N/A' }}</div>
                                        <div class="text-muted-foreground text-xs">{{ $order->color ?? 'N/A' }}</div>
                                    </td>

                                    <td class="admin-td text-muted-foreground">
                                        {{ $order->quantity ?? 'N/A' }}
                                    </td>

                                    <td class="admin-td text-muted-foreground text-xs max-w-[120px] truncate" title="{{ $order->note }}">
                                        {{ \Illuminate\Support\Str::limit($order->note ?? 'N/A', 30) }}
                                    </td>

                                    <td class="admin-td text-muted-foreground">
                                        {{ $order->store->name ?? 'N/A' }}
                                    </td>

                                    <td class="admin-td">
                                        @if($order->status == 'main_time')
                                            <span class="admin-badge-warning">Opened</span>
                                        @elseif($order->status == 'extra_time')
                                            <span class="admin-badge-danger">Extended</span>
                                        @elseif($order->status == 'not_shipped')
                                            <span class="admin-badge-critical">Not Shipped</span>
                                        @elseif($order->status == 'completed')
                                            <span class="admin-badge-success">Completed</span>
                                        @else
                                            <span class="admin-badge-neutral">{{ $order->status }}</span>
                                        @endif
                                    </td>

                                    <td class="admin-td text-muted-foreground">
                                        {{ $order->Supplier->first_name ?? 'N/A' }}
                                    </td>

                                    {{-- Main Time --}}
                                    <td class="admin-td">
                                        @if($order->status == 'main_time')
                                        <div class="text-green-600 dark:text-green-400 font-semibold text-sm">
                                            {{ $order->main_days_allocated - $order->days_spent_main }}d
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            {{ $order->days_spent_main }}/{{ $order->main_days_allocated }}
                                        </div>
                                        @elseif($order->status == 'extra_time' || $order->status == 'not_shipped')
                                        <div class="text-red-500 dark:text-red-400 text-sm font-medium">
                                            {{ $order->days_spent_main }}/{{ $order->main_days_allocated }}d
                                        </div>
                                        @else
                                        <span class="text-muted-foreground/50">—</span>
                                        @endif
                                    </td>

                                    {{-- Extra Time --}}
                                    <td class="admin-td">
                                        @if ($order->extra_days_allocated - $order->days_spent_extra > 0)
                                            @if($order->status == 'extra_time')
                                            <div class="text-red-500 dark:text-red-400 font-semibold text-sm">
                                                {{ $order->extra_days_allocated - $order->days_spent_extra }}d
                                            </div>
                                            <div class="text-xs text-muted-foreground">
                                                {{ $order->days_spent_extra }}/{{ $order->extra_days_allocated }}
                                            </div>
                                            @else
                                            <span class="text-muted-foreground/50">—</span>
                                            @endif
                                        @else
                                        <div class="text-red-500 dark:text-red-400 text-sm font-medium">
                                            {{ $order->extra_days_allocated }}/{{ $order->extra_days_allocated }}d
                                        </div>
                                        @endif
                                    </td>

                                    {{-- Days Late --}}
                                    <td class="admin-td">
                                        @if ($order->days_retarded == 0)
                                            <span class="text-muted-foreground/50">—</span>
                                        @else
                                            <div class="flex items-center gap-1 text-red-600 dark:text-red-400 font-bold text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                                                {{ $order->days_retarded }}d
                                            </div>
                                        @endif
                                    </td>

                                    <td class="admin-td text-muted-foreground text-sm">
                                        {{ $order->order_date->format('d/m/Y') }}
                                    </td>

                                    <td class="admin-td">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="admin-btn-secondary-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmAdminAction(this, 'Delete Order', 'Are you sure you want to delete this order?', 'danger');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-btn-danger-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="p-8 text-center text-muted-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-muted-foreground/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                                        No orders found.
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

    <!-- Image Preview Modal -->
    <div x-cloak x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 backdrop-blur-md"
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
