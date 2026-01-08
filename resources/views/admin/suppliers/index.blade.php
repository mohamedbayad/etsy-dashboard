<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Create New Supplier') }}
            </h2>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                Add Supplier
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse ($suppliers as $supplier)
                    <div class="rounded-xl border bg-card text-card-foreground shadow flex flex-col">

                        <div class="p-6">
                            <h3 class="text-xl font-semibold tracking-tight">
                                {{ $supplier->first_name }} {{ $supplier->last_name }}
                            </h3>
                            <p class="text-sm text-muted-foreground">{{ $supplier->specialty }}</p>
                        </div>

                        <div class="p-6 pt-0 text-muted-foreground">
                            <span class="font-bold text-foreground">{{ $supplier->orders_count }}</span>
                            Orders Actifs
                        </div>

                        <div class="flex items-center p-6 pt-0 mt-auto border-t border-border"> <div class="flex space-x-2 w-full">

                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                   class="flex-1 inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-3">
                                    Voir Orders
                                </a>

                                <!-- <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                   class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-9 w-9 p-0">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                </a>

                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Wash sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 w-9 p-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                                    </button>
                                </form> -->
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center p-12">
                        <p class="text-muted-foreground">No Supplier.</p>
                        <a href="{{ route('admin.suppliers.create') }}"
                           class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 mt-4">
                            Ajouter Lowel
                        </a>
                    </div>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
