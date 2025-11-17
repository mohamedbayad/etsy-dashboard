<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">

                <div class="p-6">
                    <h3 class="text-lg font-medium text-foreground">
                        Hello, {{ $supplierProfile->first_name }}!
                    </h3>
                    <p class="text-muted-foreground text-sm">
                        Here are your currently active orders:
                    </p>
                </div>

                <div class="p-6 pt-0">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Image</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Details (Color/Size)</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Status</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Main Time Remaining</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Extra Time Remaining</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($orders as $order)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">

                                        <td class="p-4 align-middle">
                                            @if($order->image_path)
                                                <img src="{{ asset('storage/' . $order->image_path) }}" alt="Order Image" class="h-12 w-12 rounded-md object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded-md bg-muted flex items-center justify-center text-xs">No Img</div>
                                            @endif
                                        </td>

                                        <td class="p-4 align-middle font-medium">
                                            <div>{{ $order->color ?? 'N/A' }}</div>
                                            <div class="text-muted-foreground text-xs">{{ $order->size ?? 'N/A' }}</div>
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

                                        <td class="p-4 align-middle font-medium">
                                            @if($order->status == 'main_time')
                                                <div class="text-green-600">
                                                    {{ $order->main_days_allocated - $order->days_spent_main }} days
                                                </div>
                                                <div class="text-xs text-muted-foreground">({{ $order->days_spent_main }}/{{ $order->main_days_allocated }})</div>
                                            @else
                                                <div class="text-red-600">Finished</div>
                                            @endif
                                        </td>

                                        <td class="p-4 align-middle font-medium">
                                            @if($order->status == 'extra_time')
                                                <div class="text-red-600">
                                                    {{ $order->extra_days_allocated - $order->days_spent_extra }} days
                                                </div>
                                                <div class="text-xs text-muted-foreground">({{ $order->days_spent_extra }}/{{ $order->extra_days_allocated }})</div>
                                            @else
                                                <div class="text-muted-foreground">N/A</div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-muted-foreground">
                                            You have no active orders at this time.
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
