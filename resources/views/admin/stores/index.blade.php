<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Store Management') }}</h1>
            <a href="{{ route('admin.stores.create') }}" class="admin-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add Store
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-up">

                @forelse ($stores as $store)
                    <div class="admin-stat-card group h-full flex flex-col">
                        <div class="admin-stat-card-body flex-1">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary shadow-sm group-hover:scale-110 transition-transform duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="9" rx="2"/><path d="M9 21V9"/><path d="M15 21V9"/><path d="M12 3 2 9"/><path d="M12 3 22 9"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold tracking-tight text-foreground truncate">
                                    {{ $store->name }}
                                </h3>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60 mt-0.5">Etsy Storefront</p>
                            </div>
                        </div>

                        <div class="p-6 pt-0 mt-auto border-t border-border/10 flex items-center justify-end gap-2">
                             <a href="{{ route('admin.stores.edit', $store->id) }}"
                               class="admin-btn-secondary-sm h-9 w-9 p-0" title="Edit Store">
                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </a>

                            <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmAdminAction(this, 'Delete Store', 'Are you sure you want to delete this store?', 'danger');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="admin-btn-danger-sm h-9 w-9 p-0" title="Delete Store">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full admin-panel p-12 text-center flex flex-col items-center">
                        <div class="h-16 w-16 bg-muted rounded-full flex items-center justify-center mb-4 text-muted-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-1">No stores found</h3>
                        <p class="text-muted-foreground max-w-sm mx-auto">You haven't added any stores yet. Start by creating your first store to manage your Etsy orders.</p>
                        <a href="{{ route('admin.stores.create') }}" class="admin-btn-primary mt-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Create First Store
                        </a>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>
