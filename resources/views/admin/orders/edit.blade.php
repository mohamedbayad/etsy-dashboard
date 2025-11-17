<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Modifier l\'Order') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6 space-y-6">

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-2">
                                <label for="store_id" class="text-sm font-medium leading-none">Store</label>
                                <select id="store_id" name="store_id" required
                                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option value="">Select Store...</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" {{ $order->store_id == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="status" class="text-sm font-medium leading-none">
                                    Order Status
                                </label>
                                <select id="status" name="status" required
                                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">

                                    <option value="main_time"  {{ $order->status == 'main_time' ? 'selected' : '' }}>
                                        In Progress (Main Time)
                                    </option>

                                    <option value="extra_time" {{ $order->status == 'extra_time' ? 'selected' : '' }}>
                                        In Progress (Extra Time)
                                    </option>

                                    <option value="completed"  {{ $order->status == 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                </select>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="supplier_id" class="text-sm font-medium leading-none">Supplier</label>
                                <select id="supplier_id" name="supplier_id" required
                                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                    <option value="">Select Supplier...</option>
                                    @foreach ($Suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->first_name }} {{ $supplier->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="color" class="text-sm font-medium leading-none">Color</label>
                                <input type="text" name="color" id="color"
                                       value="{{ old('color', $order->color) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="size" class="text-sm font-medium leading-none">Size</label>
                                <input type="text" name="size" id="size"
                                       value="{{ old('size', $order->size) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="main_days_allocated" class="text-sm font-medium leading-none">Main Time (per days)</label>
                                <input type="number" name="main_days_allocated" id="main_days_allocated" required
                                       value="{{ old('main_days_allocated', $order->main_days_allocated) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2">
                                <label for="extra_days_allocated" class="text-sm font-medium leading-none">Extra Time (per days)</label>
                                <input type="number" name="extra_days_allocated" id="extra_days_allocated" required
                                       value="{{ old('extra_days_allocated', $order->extra_days_allocated) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="image_path" class="text-sm font-medium leading-none">Product image</label>

                                @if ($order->image_path)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $order->image_path) }}" alt="Current Image" class="h-24 w-24 rounded-md object-cover">
                                        <p class="text-xs text-muted-foreground mt-1">Image already exist, don't upload any image if you won't change it</p>
                                    </div>
                                @endif

                                <input id="image_path" name="image_path" type="file"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 file:text-foreground file:font-medium">
                            </div>

                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-10 px-4 py-2 mx-5">
                                Close
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
