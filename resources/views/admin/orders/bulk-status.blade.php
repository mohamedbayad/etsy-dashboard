<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Bulk Status Update') }}
            </h2>

            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6 space-y-6">
                    @if (session('bulk_status_summary'))
                        @php
                            $summary = session('bulk_status_summary');
                            $updatedNames = $summary['updated_names'] ?? [];
                            $missingNames = $summary['not_found_names'] ?? [];
                        @endphp

                        <div class="rounded-lg border border-border bg-muted/40 p-4 space-y-2">
                            <div class="text-sm font-medium">
                                Updated: {{ count($updatedNames) }} names ({{ $summary['updated_orders'] ?? 0 }} orders)
                            </div>
                            @if (!empty($missingNames))
                                <div class="text-sm text-destructive">
                                    Not found: {{ count($missingNames) }} names
                                </div>
                                <div class="text-sm text-muted-foreground">
                                    {{ implode(', ', $missingNames) }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.bulk-status.update') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="customer_names" class="text-sm font-medium leading-none">Customer Names</label>
                            <textarea id="customer_names" name="customer_names" rows="6"
                                placeholder="Paste names separated by commas"
                                class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">{{ old('customer_names') }}</textarea>
                            @error('customer_names')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="text-sm font-medium leading-none">Status</label>
                            <select id="status" name="status"
                                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                <option class="dark:text-black" value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option class="dark:text-black" value="main_time" {{ old('status') == 'main_time' ? 'selected' : '' }}>Main time</option>
                                <option class="dark:text-black" value="extra_time" {{ old('status') == 'extra_time' ? 'selected' : '' }}>Extra Time</option>
                                <option class="dark:text-black" value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Complete</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Update Status
                            </button>
                            <a href="{{ route('admin.orders.bulk-status') }}"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
