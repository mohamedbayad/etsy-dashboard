<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            Edit Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel">
                <div class="admin-panel-body">

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            <div class="space-y-6">
                                <div class="rounded-xl border border-border/60 bg-muted/25 p-6">
                                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        Customer Info
                                    </h3>

                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label for="customer_name" class="admin-label">Customer Name <span class="text-red-500">*</span></label>
                                            <input type="text" id="customer_name" name="customer_name" required
                                                value="{{ old('customer_name', $order->customer_name) }}"
                                                class="admin-input">
                                            @error('customer_name') <p class="text-destructive text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="email" class="admin-label">Email</label>
                                            <input type="email" id="email" name="email"
                                                value="{{ old('email', $order->email) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="country" class="admin-label">Country</label>
                                            <input type="text" id="country" name="country"
                                                value="{{ old('country', $order->country) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2 pt-2">
                                            <label for="note" class="admin-label">Note / Instructions</label>
                                            <textarea id="note" name="note" rows="4"
                                                class="admin-input">{{ old('note', $order->note) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-xl border border-border/60 bg-muted/25 p-6">
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
                                            <label for="store_id" class="admin-label">Store <span class="text-red-500">*</span></label>
                                            <select id="store_id" name="store_id" required
                                                class="admin-input">
                                                <option value="">Select Store...</option>
                                                @foreach ($stores as $store)
                                                <option value="{{ $store->id }}"
                                                    {{ (old('store_id', $order->store_id) == $store->id) ? 'selected' : '' }}>
                                                    {{ $store->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2 ">
                                            <label for="supplier_id" class="admin-label">Supplier</label>
                                            <select id="supplier_id" name="supplier_id" required
                                                class="admin-input">
                                                <option value="">Select Supplier...</option>
                                                @foreach ($Suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->first_name }} {{ $supplier->last_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="niche_id" class="admin-label">Niche</label>
                                            <select id="niche_id" name="niche_id"
                                                class="admin-input">
                                                <option value="">Select Niche...</option>
                                                @foreach ($niches as $niche)
                                                <option value="{{ $niche->id }}" {{ (string) old('niche_id', $order->niche_id) === (string) $niche->id ? 'selected' : '' }}>
                                                    {{ $niche->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="color" class="admin-label">Color</label>
                                            <input type="text" name="color" id="color"
                                                value="{{ old('color', $order->color) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="size" class="admin-label">Size</label>
                                            <input type="text" name="size" id="size"
                                                value="{{ old('size', $order->size) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="flex items-center space-x-2 pt-2">
                                            <input type="checkbox" name="swap_color_size" id="swap_color_size"
                                                class="h-4 w-4 rounded border-border bg-background text-primary focus:ring-primary/40">
                                            <label for="swap_color_size" class="text-sm text-muted-foreground">Swap Color &amp; Size</label>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="quantity" class="admin-label">Quantity <span class="text-red-500">*</span></label>
                                            <input type="number" name="quantity" id="quantity" required min="1"
                                                value="{{ old('quantity', $order->quantity) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="price" class="admin-label">Price <span class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="price" id="price" required min="0"
                                                value="{{ old('price', $order->price) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="shipping_cost" class="admin-label">Shipping Cost (USD)</label>
                                            <input type="number" step="0.01" min="0" name="shipping_cost" id="shipping_cost"
                                                value="{{ old('shipping_cost', $order->shipping_cost) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="discount_percent" class="admin-label">Discount (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" name="discount_percent" id="discount_percent"
                                                value="{{ old('discount_percent', $order->discount_percent ?? 0) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="product_cost" class="admin-label">Product Cost (USD)</label>
                                            <input type="number" step="0.01" min="0" name="product_cost" id="product_cost"
                                                value="{{ old('product_cost', $order->product_cost) }}"
                                                class="admin-input">
                                            <p class="text-xs text-muted-foreground">Calc can auto-resolve this from niche sheet when left empty.</p>
                                        </div>

                                        <div class="space-y-2 ">
                                            <label for="order_date" class="admin-label">Order Date <span class="text-red-500">*</span></label>
                                            <input type="date" id="order_date" name="order_date" required
                                                value="{{ old('order_date', $order->order_date ? $order->order_date->format('Y-m-d') : '') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="status" class="admin-label">Order Status <span class="text-red-500">*</span></label>
                                            <select id="status" name="status" required
                                                class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                                <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="main_time" {{ old('status', $order->status) == 'main_time' ? 'selected' : '' }}>Opened Orders</option>
                                                <option value="extra_time" {{ old('status', $order->status) == 'extra_time' ? 'selected' : '' }}>Extended Orders</option>
                                                <option value="not_shipped" {{ old('status', $order->status) == 'not_shipped' ? 'selected' : '' }}>Not Shipped</option>
                                                <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Complete</option>
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="main_days_allocated" class="admin-label">Opened Orders (days) <span class="text-red-500">*</span></label>
                                            <input type="number" name="main_days_allocated" id="main_days_allocated" required
                                                value="{{ old('main_days_allocated', $order->main_days_allocated) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="extra_days_allocated" class="admin-label">Extended Orders (days) <span class="text-red-500">*</span></label>
                                            <input type="number" name="extra_days_allocated" id="extra_days_allocated" required
                                                value="{{ old('extra_days_allocated', $order->extra_days_allocated) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="image_path" class="text-sm font-medium leading-none">Product Images (Paste Ctrl+V supported)</label>

                                            <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition ease-in-out duration-150 text-center" id="paste_area">

                                                <input id="image_path" name="image_path[]" type="file" accept="image/*" multiple class="hidden" onchange="previewImage(this)">

                                                @php
                                                    $decodedImages = json_decode($order->image_path ?? '', true);
                                                    $imagePaths = (json_last_error() === JSON_ERROR_NONE && is_array($decodedImages))
                                                        ? $decodedImages
                                                        : ($order->image_path ? [$order->image_path] : []);
                                                    $primaryImage = $imagePaths[0] ?? null;
                                                @endphp
                                                <div id="preview_container" class="{{ $primaryImage ? 'flex' : 'hidden' }} flex-col items-center">
                                                    <div id="preview_list" class="flex flex-wrap justify-center gap-2 mb-3">
                                                        @foreach ($imagePaths as $path)
                                                            <img src="{{ asset('storage/' . $path) }}"
                                                                alt="Image Preview"
                                                                class="h-20 w-20 rounded-lg object-cover border border-gray-200">
                                                        @endforeach
                                                    </div>

                                                    <button type="button" onclick="removeImage()" class="text-xs text-red-500 hover:text-red-700 underline">
                                                        {{ $primaryImage ? 'Replace Images' : 'Remove Images' }}
                                                    </button>
                                                </div>

                                                <div id="placeholder_text" class="{{ $primaryImage ? 'hidden' : 'block' }} cursor-pointer" onclick="document.getElementById('image_path').click()">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center mt-2">
                                                        <span class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                            Upload a file
                                                        </span>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                                        PNG, JPG, GIF up to 10MB
                                                    </p>
                                                    <p class="text-xs text-blue-500 font-bold mt-2">
                                                        ðŸ’¡ Tip: Click anywhere and press Ctrl+V to paste images
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 md:col-span-2">
                                            <input id="compress_images" name="compress_images" type="checkbox" value="1"
                                                class="h-4 w-4 rounded border border-input text-primary focus:ring-2 focus:ring-ring">
                                            <label for="compress_images" class="text-sm text-muted-foreground">Compress uploaded images</label>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <a href="{{ route('admin.orders.index') }}"
                                class="admin-btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                class="admin-btn-primary">
                                Update Order
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
