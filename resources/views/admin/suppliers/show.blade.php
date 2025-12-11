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
                                @forelse ($supplier->orders as $order)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">

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
                                                <div class="text-green-600 dark:text-green-400">
                                                    {{ $order->main_days_allocated - $order->days_spent_main }} Days
                                                </div>
                                                <div class="text-xs text-muted-foreground">
                                                    ({{ $order->days_spent_main }}/{{ $order->main_days_allocated }})
                                                </div>
                                            @elseif($order->status == 'extra_time')
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
                                           No Orders.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
