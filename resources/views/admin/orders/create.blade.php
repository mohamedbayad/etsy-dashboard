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

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            <div class="space-y-6">

                                <div class="bg-muted/40 border rounded-lg p-6">
                                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        Customer Info
                                    </h3>

                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label for="customer_name" class="text-sm font-medium leading-none">Customer Name <span class="text-red-500">*</span></label>
                                            <input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                            @error('customer_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="email" class="text-sm font-medium leading-none">Email</label>
                                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="country" class="text-sm font-medium leading-none">Country</label>
                                            <input type="text" id="country" name="country" value="{{ old('country') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2 pt-2">
                                            <label for="note" class="text-sm font-medium leading-none">Note / Instructions</label>
                                            <textarea id="note" name="note" rows="4"
                                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">{{ old('note') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">

                                <div class="bg-muted/40 border rounded-lg p-6">
                                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                            <path d="M3 6h18" />
                                            <path d="M16 10a4 4 0 0 1-8 0" />
                                        </svg>
                                        Order Details
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        <div class="space-y-2">
                                            <label for="store_id" class="text-sm font-medium leading-none">Store <span class="text-red-500">*</span></label>
                                            <select id="store_id" name="store_id" required
                                                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                                <option value="">Select Store...</option>
                                                @foreach ($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="supplier_id" class="text-sm font-medium leading-none">Supplier</label>
                                            <select id="supplier_id" name="supplier_id" required
                                                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                                <option value="">Select Supplier...</option>
                                                @foreach ($Suppliers as $Supplier)
                                                <option value="{{ $Supplier->id }}">{{ $Supplier->first_name }} {{ $Supplier->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="color" class="text-sm font-medium leading-none">Color</label>
                                            <input type="text" name="color" id="color" value="{{ old('color') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="size" class="text-sm font-medium leading-none">Size</label>
                                            <input type="text" name="size" id="size" value="{{ old('size') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="quantity" class="text-sm font-medium leading-none">Quantity <span class="text-red-500">*</span></label>
                                            <input type="number" name="quantity" id="quantity" required min="1" value="{{ old('quantity', 1) }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="price" class="text-sm font-medium leading-none">Price <span class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="price" id="price" required min="1" value="{{ old('price') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="order_date" class="text-sm font-medium leading-none">Order Date <span class="text-red-500">*</span></label>
                                            <input type="date" id="order_date" name="order_date" required
                                                value="{{ old('order_date', isset($order) ? $order->order_date->format('Y-m-d') : date('Y-m-d')) }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="main_days_allocated" class="text-sm font-medium leading-none">Opened Orders (days) <span class="text-red-500">*</span></label>
                                            <input type="number" name="main_days_allocated" id="main_days_allocated" required value="{{ old('main_days_allocated') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="extra_days_allocated" class="text-sm font-medium leading-none">Extended Orders (days) <span class="text-red-500">*</span></label>
                                            <input type="number" name="extra_days_allocated" id="extra_days_allocated" required value="{{ old('extra_days_allocated') }}"
                                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="image_path" class="text-sm font-medium leading-none">Product Image (Paste Ctrl+V supported)</label>

                                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition ease-in-out duration-150 text-center" id="paste_area">

                                                <input id="image_path" name="image_path" type="file" accept="image/*" class="hidden" onchange="previewImage(this)">

                                                <div id="preview_container" class="hidden flex-col items-center">
                                                    <img id="preview_img" src="#" alt="Image Preview" class="max-h-64 rounded-lg shadow-md mb-3 border border-gray-200">
                                                    <button type="button" onclick="removeImage()" class="text-xs text-red-500 hover:text-red-700 underline">Remove Image</button>
                                                </div>

                                                <div id="placeholder_text" class="cursor-pointer" onclick="document.getElementById('image_path').click()">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center mt-2">
                                                        <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            Upload a file
                                                        </span>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                        PNG, JPG, GIF up to 10MB
                                                    </p>
                                                    <p class="text-xs text-blue-500 font-bold mt-2">
                                                        💡 Tip: Click anywhere and press Ctrl+V to paste image
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
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
