<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Bulk Status Update') }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel animate-fade-up">
                <div class="admin-panel-body space-y-8">
                    @if (session('bulk_status_summary'))
                        @php
                            $summary = session('bulk_status_summary');
                            $updatedNames = $summary['updated_names'] ?? [];
                            $missingNames = $summary['not_found_names'] ?? [];
                        @endphp

                        <div class="rounded-xl border border-primary/20 bg-primary/5 p-5 space-y-3 shadow-sm">
                            <div class="flex items-center gap-2 text-primary font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Update Summary
                            </div>
                            <div class="text-sm font-medium text-foreground/80 pl-7">
                                Successfully updated <span class="text-primary font-bold">{{ count($updatedNames) }}</span> customers (<span class="text-primary font-bold">{{ $summary['updated_orders'] ?? 0 }}</span> orders)
                            </div>
                            @if (!empty($missingNames))
                                <div class="mt-4 pt-4 border-t border-primary/10 pl-7">
                                    <div class="text-sm font-semibold text-destructive mb-1 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        Names Not Found ({{ count($missingNames) }})
                                    </div>
                                    <div class="text-xs text-muted-foreground leading-relaxed">
                                        {{ implode(', ', $missingNames) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.bulk-status.update') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="customer_names" class="admin-label">Customer Names</label>
                            <textarea id="customer_names" name="customer_names" rows="6"
                                placeholder="Paste names separated by commas (e.g. John Doe, Jane Smith)"
                                class="admin-input h-auto min-h-[150px]">{{ old('customer_names') }}</textarea>
                            <p class="text-[11px] text-muted-foreground italic">Tip: You can copy a list of names from Excel or a text file.</p>
                            @error('customer_names')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="admin-label">Target Status</label>
                            <select id="status" name="status" class="admin-input">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="main_time" {{ old('status') == 'main_time' ? 'selected' : '' }}>Opened Orders</option>
                                <option value="extra_time" {{ old('status') == 'extra_time' ? 'selected' : '' }}>Extended Orders</option>
                                <option value="not_shipped" {{ old('status') == 'not_shipped' ? 'selected' : '' }}>Not Shipped</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Complete</option>
                            </select>
                            @error('status')
                                <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-border/60">
                            <button type="submit" class="admin-btn-primary px-8">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Update All Statuses
                            </button>
                            <a href="{{ route('admin.orders.bulk-status') }}" class="admin-btn-secondary">
                                Clear Form
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
