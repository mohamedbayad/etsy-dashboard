<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showModal: false, activeImage: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Total Orders</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $totalOrders }}</div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Orders (Overtime)</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold text-destructive">{{ $extraTimeOrders }}</div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card text-card-foreground shadow">
                    <div class="p-6 flex flex-row items-center justify-between space-y-0 pb-2">
                        <h3 class="tracking-tight text-sm font-medium text-muted-foreground">Total Suppliers</h3>
                    </div>
                    <div class="p-6 pt-0">
                        <div class="text-2xl font-bold">{{ $totalSuppliers }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6">
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="mb-6 pb-6 border-b border-border">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                            <div class="space-y-2">
                                <label for="supplier_id" class="text-sm font-medium leading-none">
                                    Filter by Supplier
                                </label>
                                <select id="supplier_id" name="supplier_id"
                                    class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option value="">All Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->first_name }} {{ $supplier->last_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="store_id" class="text-sm font-medium leading-none">Filter by Store</label>
                                <select id="store_id" name="store_id" class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option value="">All Stores</option>
                                    @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="sort" class="text-sm font-medium leading-none">
                                    Sort by Date
                                </label>
                                <select id="sort" name="sort"
                                    class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">

                                    <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>
                                        Last Order (Newest First)
                                    </option>
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>
                                        First Order (Oldest First)
                                    </option>
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
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">

                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Image</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Details (Color/Size)</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Store</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Supplier</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Main Time</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Extra Time</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($orders as $order)
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">

                                    <td class="p-4 align-middle">
                                        @if($order->image_path)
                                        <img src="{{ asset('storage/' . $order->image_path) }}"
                                            alt="Order Image"
                                            class="h-12 w-12 rounded-md object-cover cursor-zoom-in hover:opacity-80 transition-opacity"
                                            @click="showModal = true; activeImage = '{{ asset('storage/' . $order->image_path) }}'">
                                        @else
                                        <div class="h-12 w-12 rounded-md bg-muted flex items-center justify-center text-xs text-muted-foreground">
                                            No Img
                                        </div>
                                        @endif
                                    </td>

                                    <td class="p-4 align-middle font-medium">
                                        <div>{{ $order->color ?? 'N/A' }}</div>
                                        <div class="text-muted-foreground text-xs">{{ $order->size ?? 'N/A' }}</div>
                                    </td>

                                    <td class="p-4 align-middle text-muted-foreground">
                                        {{ $order->store->name ?? 'N/A' }}
                                    </td>

                                    <td class="p-4 align-middle">
                                        @if($order->status == 'main_time')
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-orange-500 text-white">
                                            Main Time
                                        </div>

                                        @elseif($order->status == 'extra_time')
                                        <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-destructive text-destructive-foreground">
                                            Extra Time
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

                                    <td class="p-4 align-middle font-medium">
                                        @if($order->status == 'main_time')
                                        <div class="text-green-600">
                                            {{ $order->main_days_allocated - $order->days_spent_main }} Days
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            ({{ $order->days_spent_main }}/{{ $order->main_days_allocated }})
                                        </div>
                                        @elseif($order->status == 'extra_time')
                                        <div class="text-red-600">Original time has ended</div>
                                        @else
                                        <div class="text-muted-foreground">N/A</div>
                                        @endif
                                    </td>

                                    <td class="p-4 align-middle font-medium">
                                        @if($order->status == 'extra_time')
                                        <div class="text-red-600">
                                            {{ $order->extra_days_allocated - $order->days_spent_extra }} Days
                                        </div>
                                        <div class="text-xs text-muted-foreground">
                                            ({{ $order->days_spent_extra }}/{{ $order->extra_days_allocated }})
                                        </div>
                                        @else
                                        <div class="text-muted-foreground">N/A</div>
                                        @endif
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
                                    <td colspan="8" class="p-4 text-center text-muted-foreground">
                                        Makayn 7ta order db.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm"
            @click.self="showModal = false">
            <div class="relative bg-transparent max-w-4xl w-full flex justify-center">

                <button @click="showModal = false"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>

                <img :src="activeImage"
                    class="max-h-[85vh] w-auto rounded-lg shadow-2xl border border-gray-700 object-contain">
            </div>
        </div>

    </div>
</x-app-layout>
