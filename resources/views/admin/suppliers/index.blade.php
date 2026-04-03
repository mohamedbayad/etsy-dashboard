<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Suppliers Management') }}</h1>
            <a href="{{ route('admin.users.create') }}" class="admin-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Add Supplier
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-up">

                @forelse ($suppliers as $supplier)
                    <div class="admin-stat-card group h-full flex flex-col">
                        <div class="admin-stat-card-body shrink-0">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary shadow-sm group-hover:rotate-3 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl font-bold tracking-tight text-foreground truncate mb-0.5">
                                    {{ $supplier->first_name }} {{ $supplier->last_name }}
                                </h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-primary/60 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                    {{ $supplier->specialty ?: 'General Merchant' }}
                                </p>
                            </div>
                        </div>

                        <div class="px-6 pb-6 flex-1 flex flex-col">
                            <div class="flex-1 rounded-2xl bg-muted/30 p-4 ring-1 ring-border/50 mb-6">
                                <div class="text-3xl font-bold text-foreground">{{ $supplier->orders_count }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/70 mt-1">Pending Shipments</div>
                            </div>

                            <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                               class="admin-btn-secondary w-full group/btn font-bold">
                                <span>View Shipments</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover/btn:translate-x-1 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full admin-panel p-16 text-center flex flex-col items-center">
                        <div class="h-20 w-20 bg-muted rounded-full flex items-center justify-center mb-6 text-muted-foreground/50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-foreground mb-2">No suppliers registered</h3>
                        <p class="text-muted-foreground max-w-sm mx-auto leading-relaxed">Expand your network by adding your first supplier to help manage and track your Etsy order fulfillment efficiently.</p>
                        <a href="{{ route('admin.users.create') }}" class="admin-btn-primary mt-8 px-8">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Register First Supplier
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
