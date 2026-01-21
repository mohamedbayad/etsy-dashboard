<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <a href="{{ route('admin.orders.completed') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                Completed Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ showModal: false, activeImages: [], activeIndex: 0 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">

                <div class="rounded-2xl border border-border/60 bg-card/80 text-card-foreground shadow-sm ring-1 ring-border/40 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Total Orders</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $totalOrders }}</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-border/60 bg-card/80 text-card-foreground shadow-sm ring-1 ring-border/40 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Not Shipped Orders</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold text-destructive">{{ $notShippedOrders }}</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-border/60 bg-card/80 text-card-foreground shadow-sm ring-1 ring-border/40 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Orders in Extra Time</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold text-destructive">{{ $extraTimeOrders }}</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-border/60 bg-card/80 text-card-foreground shadow-sm ring-1 ring-border/40 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Completed Orders</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold text-green-600">{{ $completedOrders }}</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-border/60 bg-card/80 text-card-foreground shadow-sm ring-1 ring-border/40 transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Number of Suppliers</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $totalSuppliers }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-border/60 bg-card/80 text-card-foreground shadow-sm ring-1 ring-border/40">
                <div class="p-6">
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="mb-6 pb-6 border-b border-border">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                            <div class="space-y-2">
                                <label for="supplier_id" class="text-sm font-medium leading-none">
                                    Filter by Supplier
                                </label>
                                <select id="supplier_id" name="supplier_id"
                                    class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option class="dark:text-black" value="">All Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                    <option class="dark:text-black" value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->first_name }} {{ $supplier->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="store_id" class="text-sm font-medium leading-none">Filter by Store</label>
                                <select id="store_id" name="store_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option class="dark:text-black" value="">All Stores</option>
                                    @foreach ($stores as $store)
                                    <option class="dark:text-black" value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="sort" class="text-sm font-medium leading-none">
                                    Sort by Date
                                </label>
                                <select name="sort_retard" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option value="">Default Priority</option>
                                    <option value="most_retarded" class="dark:text-black" {{ request('sort_retard') == 'most_retarded' ? 'selected' : '' }}>
                                        Orders 9dam
                                    </option>
                                    <option value="least_retarded" class="dark:text-black" {{ request('sort_retard') == 'least_retarded' ? 'selected' : '' }}>
                                        Orders jdad
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="customer_name" class="text-sm font-medium leading-none">Customer Name</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ request('customer_name') }}"
                                    placeholder="Search customer"
                                    class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="status" class="text-sm font-medium leading-none">Status</label>
                                <select id="status" name="status" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option class="dark:text-black" value="">All Statuses</option>
                                    <option class="dark:text-black" value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option class="dark:text-black" value="main_time" {{ request('status') == 'main_time' ? 'selected' : '' }}>Opened Orders</option>
                                    <option class="dark:text-black" value="extra_time" {{ request('status') == 'extra_time' ? 'selected' : '' }}>Extended Orders</option>
                                    <option class="dark:text-black" value="not_shipped" {{ request('status') == 'not_shipped' ? 'selected' : '' }}>Not Shipped</option>
                                </select>
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                    Filter
                                </button>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-6 pt-0">
                    <div class="mb-3 text-sm text-muted-foreground">
                        Orders: {{ $orders->total() }}
                    </div>
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">

                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Customer</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Image</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Details (Color/Size)</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Quantity</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Note</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Store</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Supplier</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Opened Orders</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Extended Orders</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Not Shipped Orders</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Order Date</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($orders as $order)
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">

                                    @php
                                        $decodedImages = json_decode($order->image_path ?? '', true);
                                        $imagePaths = (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages))
                                            ? $decodedImages
                                            : ($order->image_path ? [$order->image_path] : []);
                                        $imagePath = $imagePaths[0] ?? null;
                                    @endphp
                                    <td class="p-4 align-middle text-muted-foreground">
                                        {{ $order->customer_name ?? 'N/A' }}
                                    </td>

                                    <td class="p-4 align-middle">
                                        @if($imagePath)
                                        <img src="{{ asset('storage/' . $imagePath) }}"
                                            alt="Order Image"
                                            class="h-12 w-12 rounded-md object-cover cursor-zoom-in hover:opacity-80 transition-opacity"
                                            @click="showModal = true; activeImages = {{ json_encode($imagePaths) }}; activeIndex = 0">
                                        @else
                                        <div class="h-12 w-12 rounded-md bg-muted flex items-center justify-center text-xs text-muted-foreground">
                                            No Img
                                        </div>
                                        @endif
                                    </td>

                                    <td class="p-4 align-middle font-medium">
                                        <div>{{ $order->size ?? 'N/A' }}</div>
                                        <div class="text-muted-foreground text-xs">{{ $order->color ?? 'N/A' }}</div>
                                    </td>

                                    <td class="p-4 align-middle text-muted-foreground">
                                        {{ $order->quantity ?? 'N/A' }}
                                    </td>

                                    <td class="p-4 align-middle text-muted-foreground">
                                        {{ \Illuminate\Support\Str::limit($order->note ?? 'N/A', 40) }}
                                    </td>

                                    <td class="p-4 align-middle text-muted-foreground">
                                        {{ $order->store->name ?? 'N/A' }}
                                    </td>

                                    <td class="p-4 align-middle">
                                        @if($order->status == 'main_time')
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-orange-500 text-white">
                                            Opened Orders
                                        </div>

                                        @elseif($order->status == 'extra_time')
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-destructive text-destructive-foreground">
                                            Extended Orders
                                        </div>

                                        @elseif($order->status == 'not_shipped')
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-red-700 text-white">
                                            Not Shipped
                                        </div>

                                        @elseif($order->status == 'completed')
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-green-600 text-white">
                                            Completed
                                        </div>

                                        @else
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-muted text-muted-foreground">
                                            {{ $order->status }}
                                        </div>
                                        @endif
                                    </td>

                                    <td class="p-4 align-middle text-muted-foreground">
                                        {{ $order->Supplier->first_name ?? 'N/A' }}
                                    </td>


                                    <!-- Main Time -->
                                    <td class="p-4 align-middle font-medium">
                                        @if($order->status == 'main_time')
                                        <div class="text-green-600 dark:text-green-400">
                                            {{ $order->main_days_allocated - $order->days_spent_main }} Days
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            ({{ $order->days_spent_main }}/{{ $order->main_days_allocated }})
                                        </div>
                                        @elseif($order->status == 'extra_time' || $order->status == 'not_shipped')
                                        <div class=" text-base text-red-600 dark:text-red-400"> ({{ $order->days_spent_main }}/{{ $order->main_days_allocated }}) <span class=" text-xs ">days</span>
                                        </div>
                                        @else
                                        <div class="text-muted-foreground">N/A</div>
                                        @endif
                                    </td>

                                    <!-- Extra Time -->
                                    <td class="p-4 align-middle font-medium">
                                        @if ( $order->extra_days_allocated - $order->days_spent_extra > 0)
                                        @if($order->status == 'extra_time')
                                        <div class="text-red-600 dark:text-red-400">
                                            {{ $order->extra_days_allocated - $order->days_spent_extra }} Days
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            ({{ $order->days_spent_extra }}/{{ $order->extra_days_allocated }})
                                        </div>
                                        @else
                                        <div class="text-muted-foreground">N/A</div>
                                        @endif
                                        @else
                                        <div class=" text-base text-red-600 dark:text-red-400"> ({{ $order->extra_days_allocated }}/{{ $order->extra_days_allocated }}) <span class=" text-xs ">days</span>
                                        </div>
                                        @endif
                                    </td>

                                    <!-- Days Retarded -->
                                    <td class="p-4 align-middle font-medium">
                                        @if ($order->days_retarded == 0)
                                            <div class=" text-white font-extrabold flex items-center">
                                                N/A
                                            </div>
                                        @else
                                            <div class="text-red-700 font-extrabold flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $order->days_retarded }} Days (Retard)
                                            </div>
                                        @endif
                                    </td>



                                    <td class="p-4 align-middle font-medium">
                                        {{ $order->order_date->format('d/m/Y') }}
                                    </td>

                                    <td class="p-4 align-middle">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Wash sure bghiti tms7?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 px-3">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="p-4 text-center text-muted-foreground">
                                        No Order Now.
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

        <!-- image popup -->
        <div x-show="showModal"
            style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 dark:bg-black/90 p-4 backdrop-blur-sm"
            @click.self="showModal = false">
            <div class="relative bg-transparent max-w-5xl w-full">

                <button @click="showModal = false"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 dark:hover:text-gray-400 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <div class="flex gap-4">
                    <div class="flex flex-col gap-2 max-h-[80vh] overflow-auto pr-2">
                        <template x-for="(img, idx) in activeImages" :key="idx">
                            <button type="button" @click="activeIndex = idx" class="rounded-md focus:outline-none focus:ring-2 focus:ring-ring">
                                <img :src="`{{ asset('storage') }}/` + img"
                                    class="h-16 w-16 rounded-md object-cover border border-border"
                                    :class="activeIndex === idx ? 'ring-2 ring-white' : 'opacity-80'">
                            </button>
                        </template>
                    </div>
                    <div class="relative flex-1 flex items-center justify-center">
                        <button type="button"
                            class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 text-black shadow"
                            x-show="activeImages.length > 1"
                            @click="activeIndex = (activeIndex - 1 + activeImages.length) % activeImages.length">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <img :src="activeImages.length ? `{{ asset('storage') }}/` + activeImages[activeIndex] : ''"
                            class="max-h-[85vh] w-auto rounded-lg shadow-2xl border border-border object-contain">
                        <button type="button"
                            class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-white/90 p-2 text-black shadow"
                            x-show="activeImages.length > 1"
                            @click="activeIndex = (activeIndex + 1) % activeImages.length">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
