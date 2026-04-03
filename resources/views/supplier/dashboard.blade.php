<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <div>
                <h1 class="admin-page-title">{{ __('Supplier Portal') }}</h1>
                <p class="text-xs text-muted-foreground mt-1 font-medium italic">Welcome back, {{ $supplierProfile->first_name }}! Managing your active shipments.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-[10px] font-bold text-primary uppercase tracking-widest whitespace-nowrap">
                    {{ $supplierProfile->specialty ?: 'General Merchant' }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ showModal: false, activeImages: [], activeIndex: 0 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel animate-fade-up">
                <div class="admin-panel-body">
                    <form action="{{ route('supplier.dashboard') }}" method="GET" class="admin-subtle-divider">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                            <div class="space-y-2">
                                <label for="customer_name" class="admin-label">Customer Search</label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    <input id="customer_name" name="customer_name" type="text" value="{{ request('customer_name') }}"
                                        placeholder="Name or ID..."
                                        class="admin-input pl-9">
                                </div>
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

                            <div class="space-y-2">
                                <label for="sort_retard" class="admin-label">Priority Sort</label>
                                <select id="sort_retard" name="sort_retard" class="admin-input">
                                    <option value="">Default Priority</option>
                                    <option value="most_retarded" {{ request('sort_retard') == 'most_retarded' ? 'selected' : '' }}>Oldest / Overdue</option>
                                    <option value="least_retarded" {{ request('sort_retard') == 'least_retarded' ? 'selected' : '' }}>Newest Orders</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="submit" class="admin-btn-primary flex-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    Filter
                                </button>
                                <a href="{{ route('supplier.dashboard') }}" class="admin-btn-secondary" title="Clear Filters">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-sm font-semibold text-muted-foreground/80">
                            Found <span class="text-foreground">{{ $orders->total() }}</span> active orders
                        </div>
                    </div>

                    <div class="admin-table-shell">
                        <table class="admin-table">
                            <thead class="admin-table-head">
                                <tr class="admin-tr">
                                    <th class="admin-th">Customer</th>
                                    <th class="admin-th">Product</th>
                                    <th class="admin-th">Details</th>
                                    <th class="admin-th">Status</th>
                                    <th class="admin-th">Timing</th>
                                    <th class="admin-th">Retard</th>
                                    <th class="admin-th">Order Date</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($orders as $order)
                                <tr class="admin-tr group">
                                    @php
                                        $decodedImages = json_decode($order->image_path ?? '', true);
                                        $imagePaths = (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages))
                                            ? $decodedImages
                                            : ($order->image_path ? [$order->image_path] : []);
                                        $imagePath = $imagePaths[0] ?? null;
                                    @endphp
                                    <td class="admin-td">
                                        <div class="font-bold text-foreground text-sm">{{ $order->customer_name ?? 'N/A' }}</div>
                                        <div class="text-[10px] uppercase tracking-wider text-muted-foreground font-medium">ID: #{{ $order->id }}</div>
                                    </td>

                                    <td class="admin-td">
                                        @if($imagePath)
                                        <div class="group relative h-12 w-12 cursor-zoom-in" @click="showModal = true; activeImages = {{ json_encode($imagePaths) }}; activeIndex = 0">
                                            <img src="{{ asset('storage/' . $imagePath) }}"
                                                alt="Order Image"
                                                class="h-12 w-12 rounded-lg object-cover ring-1 ring-border group-hover:opacity-80 transition-all group-hover:scale-105">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/><line x1="11" x2="11" y1="8" y2="14"/><line x1="8" x2="14" y1="11" y2="11"/></svg>
                                            </div>
                                        </div>
                                        @else
                                        <div class="h-12 w-12 rounded-lg bg-muted flex items-center justify-center text-muted-foreground/50 border border-dashed border-border text-[10px] font-bold">
                                            NO IMG
                                        </div>
                                        @endif
                                    </td>

                                    <td class="admin-td">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-foreground text-xs">{{ $order->quantity ?? '0' }} units</span>
                                            <span class="text-[11px] text-muted-foreground font-medium truncate max-w-[150px] mt-0.5">
                                                {{ $order->size ?: 'No Size' }} • {{ $order->color ?: 'No Color' }}
                                            </span>
                                            @if($order->note)
                                                <span class="text-[10px] italic text-primary mt-1 border-l-2 border-primary/30 pl-1.5 line-clamp-1" title="{{ $order->note }}">
                                                    Note: {{ \Illuminate\Support\Str::limit($order->note, 30) }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="admin-td">
                                        @if($order->status == 'main_time')
                                            <span class="admin-badge-warning shadow-sm">Opened</span>
                                        @elseif($order->status == 'extra_time')
                                            <span class="admin-badge-danger shadow-sm">Extended</span>
                                        @elseif($order->status == 'not_shipped')
                                            <span class="admin-badge-danger bg-red-600/10 text-red-600 border-red-600/20 shadow-sm">Not Shipped</span>
                                        @elseif($order->status == 'completed')
                                            <span class="admin-badge-success shadow-sm">Completed</span>
                                        @else
                                            <span class="admin-badge-muted">{{ $order->status }}</span>
                                        @endif
                                    </td>

                                    <td class="admin-td">
                                        @if($order->status == 'main_time')
                                            <div class="flex items-center gap-1.5 font-bold text-green-600 dark:text-green-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                {{ $order->main_days_allocated - $order->days_spent_main }}d Left
                                            </div>
                                            <div class="text-[10px] text-muted-foreground mt-0.5 uppercase tracking-tighter">
                                                {{ $order->days_spent_main }}/{{ $order->main_days_allocated }}d Use
                                            </div>
                                        @elseif($order->status == 'extra_time')
                                            <div class="flex items-center gap-1.5 font-bold text-destructive">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                                {{ $order->extra_days_allocated - $order->days_spent_extra }}d Left
                                            </div>
                                            <div class="text-[10px] text-muted-foreground mt-0.5 uppercase tracking-tighter">
                                                {{ $order->days_spent_extra }}/{{ $order->extra_days_allocated }}d Extra
                                            </div>
                                        @else
                                            <div class="text-muted-foreground/40 font-bold italic text-xs">Finalized</div>
                                        @endif
                                    </td>

                                    <td class="admin-td">
                                        @if ($order->days_retarded > 0)
                                            <div class="flex items-center gap-1.5 font-bold text-red-600 bg-red-600/5 px-2 py-1 rounded-lg border border-red-600/20 ring-4 ring-red-600/5 animate-pulse">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                                {{ $order->days_retarded }}d Retard
                                            </div>
                                        @else
                                            <div class="text-muted-foreground/30 font-bold italic text-xs">ON TIME</div>
                                        @endif
                                    </td>

                                    <td class="admin-td font-medium text-muted-foreground text-xs">
                                        {{ $order->order_date->format('d M, Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="p-16 text-center text-muted-foreground italic bg-muted/10">
                                       <div class="flex flex-col items-center gap-2">
                                           <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-10 mb-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                           No active orders assigned to you currently.
                                       </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
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
</x-app-layout>
