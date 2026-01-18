<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                Orders By: {{ $supplier->first_name }} {{ $supplier->last_name }}
            </h2>

            <a href="{{ route('admin.suppliers.index') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                &larr; Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6">
                    <form action="{{ route('admin.suppliers.show', $supplier->id) }}" method="GET" class="mb-6 pb-6 border-b border-border">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="space-y-2">
                                <label for="customer_name" class="text-sm font-medium leading-none">Customer Name</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ request('customer_name') }}"
                                    placeholder="Search customer"
                                    class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                            </div>

                            <div class="flex space-x-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                    Filter
                                </button>
                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>

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
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Store</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Supplier</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Opened Orders</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Extended Orders</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($orders as $order)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">

                                        <td class="p-4 align-middle text-muted-foreground">
                                            {{ $order->customer_name ?? 'N/A' }}
                                        </td>

                                        <td class="p-4 align-middle">
                                            @if($order->image_path)
                                                <img src="{{ asset('storage/' . $order->image_path) }}" alt="Order Image" class="h-12 w-12 rounded-md object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded-md bg-muted flex items-center justify-center text-muted-foreground text-xs">
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

                                        <td class="p-4 align-middle font-medium">
                                            @if($order->status == 'main_time')
                                                <div class="text-green-600 dark:text-green-400">
                                                    {{ $order->main_days_allocated - $order->days_spent_main }} Days
                                                </div>
                                                <div class="text-xs text-muted-foreground">
                                                    ({{ $order->days_spent_main }}/{{ $order->main_days_allocated }})
                                                </div>
                                            @elseif($order->status == 'extra_time' || $order->status == 'not_shipped')
                                                <div class="text-red-600 dark:text-red-400">Exhausted</div>
                                            @else
                                                <div class="text-muted-foreground">N/A</div>
                                            @endif
                                        </td>

                                        <td class="p-4 align-middle font-medium">
                                            @if($order->status == 'extra_time')
                                                <div class="text-red-600 dark:text-red-400">
                                                    {{ $order->extra_days_allocated - $order->days_spent_extra }} Days
                                                </div>
                                                <div class="text-xs text-muted-foreground">
                                                    ({{ $order->days_spent_extra }}/{{ $order->extra_days_allocated }})
                                                </div>
                                            @elseif($order->status == 'not_shipped')
                                                <div class="text-red-600 dark:text-red-400">Time has been passed</div>
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
                                        <td colspan="9" class="p-4 text-center text-muted-foreground">
                                           No Orders.
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
    </div>
</x-app-layout>
