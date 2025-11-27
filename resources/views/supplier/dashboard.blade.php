<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showModal: false, activeImage: '' }">
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

                <div class="p-6">
                    <form action="{{ route('supplier.dashboard') }}" method="GET" class="mb-6 pb-6 border-b border-border">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

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
