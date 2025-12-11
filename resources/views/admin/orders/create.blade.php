<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Ajouter un Nouveau Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6 space-y-6">

                    <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-2">
                                <label for="store_id" class="text-sm font-medium leading-none">
                                    Store
                                </label>
                                <select id="store_id" name="store_id" required
                                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option class="dark:text-black" value="">Select Store...</option>
                                    @foreach ($stores as $store)
                                        <option class="dark:text-black" value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="supplier_id" class="text-sm font-medium leading-none">
                                    Supplier
                                </label>
                                <select id="supplier_id" name="supplier_id" required
                                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option class="dark:text-black" value="">Select Supplier...</option>
                                    @foreach ($Suppliers as $Supplier)
                                        <option class="dark:text-black" value="{{ $Supplier->id }}">{{ $Supplier->first_name }} {{ $Supplier->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="color" class="text-sm font-medium leading-none">
                                    Color
                                </label>
                                <input type="text" name="color" id="color"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="size" class="text-sm font-medium leading-none">
                                    Size
                                </label>
                                <input type="text" name="size" id="size"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="main_days_allocated" class="text-sm font-medium leading-none">
                                    Main Time (per days)
                                </label>
                                <input type="number" name="main_days_allocated" id="main_days_allocated" required
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="extra_days_allocated" class="text-sm font-medium leading-none">
                                    Extra Time (per days)
                                </label>
                                <input type="number" name="extra_days_allocated" id="extra_days_allocated" required
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2 ">
                                <label for="order_date" class="text-sm font-medium leading-none">Date de la commande</label>
                                <input type="date" id="order_date" name="order_date" required
                                    value="{{ old('order_date', isset($order) ? $order->order_date->format('Y-m-d') : date('Y-m-d')) }}"
                                    class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm focus:ring-2 focus:ring-ring">
                            </div>

                            <div class="space-y-2"> <label for="image_path" class="text-sm font-medium leading-none">
                                    Product Image
                                </label>
                                <input id="image_path" name="image_path" type="file"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2
                                              file:text-foreground file:font-medium">
                            </div>

                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2 mx-5">
                                Close
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Save Order
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
