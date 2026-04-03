<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Order Profitability Calc') }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
                Orders
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @php
                $revenueBadgeClass = 'inline-flex items-center rounded-md border border-green-200 bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 whitespace-nowrap';
                $chargeBadgeClass = 'inline-flex items-center rounded-md border border-red-200 bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 whitespace-nowrap';
                $neutralBadgeClass = 'inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-xs font-semibold text-slate-700 whitespace-nowrap';
            @endphp

            <div class="admin-panel">
                <div class="admin-panel-body">
                    <form action="{{ route('admin.calculations.index') }}" method="GET" class="admin-filter-grid items-end">
                        <div class="space-y-2">
                            <label for="month" class="admin-label">Month <span class="text-red-500">*</span></label>
                            <input type="month" id="month" name="month" required value="{{ $filters['month'] }}" class="admin-input">
                        </div>

                        <div class="space-y-2">
                            <label for="niche" class="admin-label">Niche <span class="text-red-500">*</span></label>
                            <select id="niche" name="niche" required class="admin-input">
                                <option value="all" {{ $filters['niche'] === 'all' ? 'selected' : '' }}>All Niches</option>
                                <option value="unassigned" {{ $filters['niche'] === 'unassigned' ? 'selected' : '' }}>Unassigned Niche Orders</option>
                                @foreach ($niches as $niche)
                                    <option value="{{ $niche->id }}" {{ (string) $filters['niche'] === (string) $niche->id ? 'selected' : '' }}>{{ $niche->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="store_id" class="admin-label">Store <span class="text-red-500">*</span></label>
                            <select id="store_id" name="store_id" required class="admin-input">
                                <option value="all" {{ $filters['store_id'] === 'all' ? 'selected' : '' }}>All Stores</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}" {{ (string) $filters['store_id'] === (string) $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="discount_value" class="admin-label">Discount Value (%)</label>
                            <input type="number" step="0.01" min="0" max="100" id="discount_value" name="discount_value" value="{{ $filters['discount_value'] }}" class="admin-input" placeholder="Exact % (e.g. 15)">
                        </div>

                        <div class="xl:col-span-4 flex flex-wrap gap-2">
                            <button type="submit" class="admin-btn-primary">
                                Apply Filters
                            </button>
                            <a href="{{ route('admin.calculations.index') }}" class="admin-btn-secondary">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="admin-stat-card animate-fade-up">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-blue-500 to-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Total Ads (Month)</p>
                            <p class="text-xl font-bold mt-0.5 text-destructive">-${{ number_format($adsSummary['total_ads'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:100ms">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-violet-500 to-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Orders (Month)</p>
                            <p class="text-xl font-bold mt-0.5">{{ $adsSummary['orders_count'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:200ms">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-rose-500 to-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Ads Share / Order</p>
                            <p class="text-xl font-bold mt-0.5 text-destructive">-${{ number_format($adsSummary['ads_share_per_order'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:300ms">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon {{ $adsSummary['month_state'] === 'full' ? 'bg-gradient-to-br from-emerald-500 to-green-600' : 'bg-gradient-to-br from-amber-500 to-orange-600' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Ads Month State</p>
                            <p class="text-xl font-bold mt-0.5 {{ $adsSummary['month_state'] === 'full' ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $adsSummary['month_state'] === 'full' ? 'Full' : 'Partial' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-body">
                    <h3 class="mb-4 text-base font-semibold">Add Monthly Ads Entry</h3>
                    <form action="{{ route('admin.calculations.ads.store') }}" method="POST" class="grid grid-cols-1 gap-4 md:grid-cols-5 items-end">
                        @csrf
                        <input type="hidden" name="month" value="{{ $filters['month'] }}">
                        <input type="hidden" name="niche" value="{{ $filters['niche'] }}">
                        <input type="hidden" name="store_id" value="{{ $filters['store_id'] }}">
                        <input type="hidden" name="discount_value" value="{{ $filters['discount_value'] }}">

                        <div class="space-y-2">
                            <label for="amount" class="admin-label">Amount (USD)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" required class="admin-input">
                        </div>

                        <div class="space-y-2">
                            <label for="entry_date" class="admin-label">Entry Date</label>
                            <input type="date" name="entry_date" id="entry_date" required value="{{ now()->format('Y-m-d') }}" class="admin-input">
                        </div>

                        <div class="space-y-2">
                            <label for="coverage" class="admin-label">Coverage</label>
                            <select name="coverage" id="coverage" class="admin-input">
                                <option value="partial">Partial Amount</option>
                                <option value="full">Full Month Amount</option>
                            </select>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="note" class="admin-label">Note (optional)</label>
                            <input type="text" name="note" id="note" class="admin-input">
                        </div>

                        <div class="md:col-span-5">
                            <button type="submit" class="admin-btn-primary">
                                Save Ads Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-body">
                    <h3 class="mb-4 text-base font-semibold">Monthly Ads Entries</h3>
                    <div class="admin-table-shell">
                        <table class="admin-table">
                            <thead class="admin-table-head">
                                <tr class="border-b">
                                    <th class="admin-th">Entry Date</th>
                                    <th class="admin-th">Amount (USD)</th>
                                    <th class="admin-th">Coverage</th>
                                    <th class="admin-th">Note</th>
                                    <th class="admin-th">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($adsSummary['entries'] as $entry)
                                    <tr class="admin-tr">
                                        <td class="admin-td text-muted-foreground">{{ $entry->entry_date?->format('d/m/Y') }}</td>
                                        <td class="admin-td">
                                            <span class="{{ $chargeBadgeClass }}">-${{ number_format($entry->amount, 2) }}</span>
                                        </td>
                                        <td class="admin-td">
                                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium {{ $entry->is_full_month ? 'border-green-200 bg-green-100 text-green-800' : 'border-yellow-200 bg-yellow-100 text-yellow-800' }}">
                                                {{ $entry->is_full_month ? 'Full' : 'Partial' }}
                                            </span>
                                        </td>
                                        <td class="admin-td text-muted-foreground">{{ $entry->note ?: 'N/A' }}</td>
                                        <td class="admin-td text-muted-foreground">{{ $entry->created_at?->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-muted-foreground">No ads entries for this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-panel-body">
                    <h3 class="mb-4 text-base font-semibold">Calculated Orders</h3>
                    <div class="admin-table-shell">
                        <table class="admin-table min-w-[1700px]">
                            <thead class="admin-table-head">
                                <tr class="admin-tr">
                                    <th class="admin-th w-[80px]">Image</th>
                                    <th class="admin-th w-[120px]">Order Date</th>
                                    <th class="admin-th w-[150px]">Store</th>
                                    <th class="admin-th w-[170px]">Client Name</th>
                                    <th class="admin-th w-[130px]">Country</th>
                                    <th class="admin-th w-[150px]">Niche</th>
                                    <th class="admin-th w-[100px]">Size</th>
                                    <th class="admin-th w-[120px]">Price</th>
                                    <th class="admin-th w-[110px]">Disc. (%)</th>
                                    <th class="admin-th w-[130px]">Etsy Fee</th>
                                    <th class="admin-th w-[130px]">Cost</th>
                                    <th class="admin-th w-[120px]">Shipping</th>
                                    <th class="admin-th w-[110px]">Ads</th>
                                    <th class="admin-th w-[130px]">Profit</th>
                                    <th class="admin-th">Status & Notes</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @forelse ($calculationRows as $row)
                                    <tr class="admin-tr">
                                        @php
                                            $decodedImages = json_decode($row['order']->image_path ?? '', true);
                                            $imagePaths = (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages))
                                                ? $decodedImages
                                                : ($row['order']->image_path ? [$row['order']->image_path] : []);
                                            $firstImage = $imagePaths[0] ?? null;
                                        @endphp
                                        <td class="admin-td">
                                            @if ($firstImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($firstImage))
                                                <div class="relative h-10 w-10 overflow-hidden rounded-lg border border-border/50 group/img">
                                                    <img src="{{ asset('storage/' . $firstImage) }}" alt="Order" class="h-full w-full object-cover transition-transform group-hover/img:scale-110" />
                                                </div>
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-dashed border-border text-[10px] text-muted-foreground/50 font-bold">
                                                    NO IMG
                                                </div>
                                            @endif
                                        </td>
                                        <td class="admin-td text-muted-foreground font-medium">{{ $row['order']->order_date?->format('d/m/Y') }}</td>
                                        <td class="admin-td">
                                            <div class="truncate max-w-[140px] text-xs font-semibold text-foreground">
                                                {{ $row['order']->store?->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="admin-td">
                                            <div class="truncate max-w-[160px] text-xs font-semibold text-foreground">
                                                {{ $row['order']->customer_name ?: 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="admin-td">
                                            <div class="truncate max-w-[120px] text-xs text-muted-foreground">
                                                {{ $row['order']->country ?: 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="admin-td">
                                            <div class="font-bold text-xs truncate max-w-[140px]">{{ $row['order']->niche?->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="admin-td text-muted-foreground text-xs">{{ $row['order']->size ?: 'N/A' }}</td>
                                        <td class="admin-td">
                                            <span class="admin-badge-success">${{ number_format($row['order_price'], 2) }}</span>
                                        </td>
                                        <td class="admin-td">
                                            <span class="admin-badge-danger">{{ number_format($row['discount_percent'], 2) }}%</span>
                                        </td>
                                        <td class="admin-td">
                                            <span class="admin-badge-danger">-${{ number_format($row['etsy_fee'], 2) }}</span>
                                        </td>
                                        <td class="admin-td">
                                            @if ($row['product_cost'] === null)
                                                <span class="admin-badge-neutral">N/A</span>
                                            @else
                                                <span class="admin-badge-danger">-${{ number_format($row['product_cost'], 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="admin-td">
                                            <span class="admin-badge-danger">-${{ number_format($row['shipping_cost'], 2) }}</span>
                                        </td>
                                        <td class="admin-td">
                                            <span class="admin-badge-danger">-${{ number_format($row['ads_share'], 2) }}</span>
                                        </td>
                                        <td class="admin-td font-bold">
                                            @if($row['final_profit'] === null)
                                                <span class="admin-badge-neutral font-bold">N/A</span>
                                            @elseif($row['final_profit'] > 0)
                                                <span class="admin-badge-success font-bold text-sm bg-green-500/10 border-green-500/20">${{ number_format($row['final_profit'], 2) }}</span>
                                            @else
                                                <span class="admin-badge-danger font-bold text-sm bg-red-500/10 border-red-500/20">${{ number_format($row['final_profit'], 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="admin-td">
                                            @if ($row['match_status'] === 'resolved')
                                                <span class="admin-badge-success mb-1">Resolved</span>
                                                <p class="text-[10px] text-muted-foreground italic leading-tight">{{ $row['cost_source'] === 'niche_sheet' ? 'From niche DH/10 sheet.' : 'Using saved cost.' }}</p>
                                            @else
                                                <span class="admin-badge-danger mb-1">{{ ucfirst(str_replace('_', ' ', $row['match_status'])) }}</span>
                                                <p class="text-[10px] text-red-500 leading-tight font-medium">{{ $row['warning'] }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="p-16 text-center text-muted-foreground italic">
                                            No orders matched the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="admin-stat-card animate-fade-up">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-slate-500 to-slate-700">
                             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10h10V2z"/><path d="M22 12H12v10h10V12z"/><path d="M12 12H2v10h10V12z"/><path d="M22 2H12v10h10V2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Filtered Orders</p>
                            <p class="text-xl font-bold mt-0.5">{{ $calculationTotals['orders_count'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:100ms">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-emerald-500 to-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/><path d="M15 3v18"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Total Effective Rev.</p>
                            <p class="text-xl font-bold mt-0.5 text-green-600">${{ number_format($calculationTotals['effective_revenue'], 2) }}</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:200ms">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-blue-500 to-indigo-600">
                             <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Total Profit (Comp.)</p>
                            <p class="text-xl font-bold mt-0.5 {{ $calculationTotals['profit'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                ${{ number_format($calculationTotals['profit'], 2) }}
                            </p>
                            <p class="text-[10px] text-muted-foreground mt-0.5 italic">For {{ $calculationTotals['profit_computed_count'] }} order(s)</p>
                        </div>
                    </div>
                </div>

                <div class="admin-stat-card animate-fade-up" style="animation-delay:300ms">
                    <div class="admin-stat-card-body">
                        <div class="admin-stat-icon bg-gradient-to-br from-red-500 to-rose-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/70">Missing Product Cost</p>
                            <p class="text-xl font-bold mt-0.5 text-red-600">{{ $calculationTotals['missing_product_cost_count'] }}</p>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</x-app-layout>
