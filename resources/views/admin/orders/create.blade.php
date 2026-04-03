<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Add New Order') }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="admin-btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="quickPasteOrderImport()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel">
                <div class="admin-panel-body space-y-6">
                    <div class="rounded-xl border border-border/70 bg-muted/20 p-5 space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="text-base font-semibold text-foreground">Quick Paste Order Import</h3>
                                <p class="text-sm text-muted-foreground">Paste structured order text and auto-fill the form.</p>
                            </div>
                            <button type="button"
                                @click="showImporter = !showImporter"
                                class="admin-btn-secondary">
                                <span x-text="showImporter ? 'Hide Paste Panel' : 'Paste Order Details'"></span>
                            </button>
                        </div>

                        <div x-show="showImporter" x-transition style="display: none;" class="space-y-4">
                            <div class="space-y-2">
                                <label for="quick_paste_text" class="text-sm font-medium leading-none">Pasted Text</label>
                                <textarea id="quick_paste_text"
                                    x-model="rawText"
                                    rows="9"
                                    placeholder="Paste order details here..."
                                    class="admin-input"></textarea>
                            </div>

                            <div class="rounded-md border border-border bg-background p-3">
                                <p class="text-xs font-medium text-foreground mb-2">Example format:</p>
                                <pre class="text-xs text-muted-foreground whitespace-pre-wrap leading-relaxed">*Name* : TAOUFIK EL BOUAIDI
*Email* : tuf@100kends.com
*Phone* : 00215120

*VAT* : this is a vat number
*Price* : 1001$
*size* : 35 cm
*Color* : bronze
*Quantity* : 1
*Country* : Morocco
*Product Link* : 100kends.com
*Product name* : lamp
*Product Cost* : 50$
*Shipping Price* : 40$
*Discount %* : 10%

*Note* : 3afaaak
*Store* : 100kends
*Supplier* : TAOUFIK RUGS
*DATE ORDER*: Today
*Opened Orders (days)* : 7
*Extended Orders (days)* : 21</pre>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                    @click="parseAndFill()"
                                    class="admin-btn-primary">
                                    Parse &amp; Fill
                                </button>
                                <button type="button"
                                    @click="pasteSample()"
                                    class="admin-btn-secondary">
                                    Paste Sample
                                </button>
                                <button type="button"
                                    @click="clearImporter()"
                                    class="admin-btn-secondary">
                                    Clear
                                </button>
                            </div>

                            <div x-show="feedback.message" style="display: none;"
                                class="rounded-md border p-3 text-sm"
                                :class="{
                                    'border-green-500/40 bg-green-500/10 text-green-700 dark:text-green-300': feedback.type === 'success',
                                    'border-amber-500/40 bg-amber-500/10 text-amber-700 dark:text-amber-300': feedback.type === 'warning',
                                    'border-red-500/40 bg-red-500/10 text-red-700 dark:text-red-300': feedback.type === 'error'
                                }">
                                <p x-text="feedback.message"></p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-order-create-form>
                        @csrf

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
                                            <label for="customer_name" class="text-sm font-medium leading-none">Customer Name <span class="text-red-500">*</span></label>
                                            <input type="text" id="customer_name" name="customer_name" required value="{{ old('customer_name') }}"
                                                class="admin-input">
                                            @error('customer_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="email" class="text-sm font-medium leading-none">Email</label>
                                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="country" class="text-sm font-medium leading-none">Country</label>
                                            <input type="text" id="country" name="country" value="{{ old('country') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2 pt-2">
                                            <label for="note" class="text-sm font-medium leading-none">Note / Instructions</label>
                                            <textarea id="note" name="note" rows="4"
                                                class="admin-input">{{ old('note') }}</textarea>
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
                                            <label for="store_id" class="text-sm font-medium leading-none">Store <span class="text-red-500">*</span></label>
                                            <select id="store_id" name="store_id" required
                                                class="admin-input">
                                                <option value="">Select Store...</option>
                                                @foreach ($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="supplier_id" class="text-sm font-medium leading-none">Supplier</label>
                                            <select id="supplier_id" name="supplier_id" required
                                                class="admin-input">
                                                <option value="">Select Supplier...</option>
                                                @foreach ($Suppliers as $Supplier)
                                                <option value="{{ $Supplier->id }}">{{ $Supplier->first_name }} {{ $Supplier->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="niche_id" class="text-sm font-medium leading-none">Niche</label>
                                            <select id="niche_id" name="niche_id"
                                                class="admin-input">
                                                <option value="">Select Niche...</option>
                                                @foreach ($niches as $niche)
                                                <option value="{{ $niche->id }}" {{ (string) old('niche_id') === (string) $niche->id ? 'selected' : '' }}>{{ $niche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="color" class="text-sm font-medium leading-none">Color</label>
                                            <input type="text" name="color" id="color" value="{{ old('color') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="size" class="text-sm font-medium leading-none">Size</label>
                                            <input type="text" name="size" id="size" value="{{ old('size') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="quantity" class="text-sm font-medium leading-none">Quantity <span class="text-red-500">*</span></label>
                                            <input type="number" name="quantity" id="quantity" required min="1" value="{{ old('quantity', 1) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="price" class="text-sm font-medium leading-none">Price <span class="text-red-500">*</span></label>
                                            <input type="number" step="0.01" name="price" id="price" required min="1" value="{{ old('price') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="shipping_cost" class="text-sm font-medium leading-none">Shipping Cost (USD)</label>
                                            <input type="number" step="0.01" min="0" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="discount_percent" class="text-sm font-medium leading-none">Discount (%)</label>
                                            <input type="number" step="0.01" min="0" max="100" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', 0) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="product_cost" class="text-sm font-medium leading-none">Product Cost (USD)</label>
                                            <input type="number" step="0.01" min="0" name="product_cost" id="product_cost" value="{{ old('product_cost') }}"
                                                class="admin-input">
                                            <p class="text-xs text-muted-foreground">If left empty, Calc can auto-resolve from niche sheet by size.</p>
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="order_date" class="text-sm font-medium leading-none">Order Date <span class="text-red-500">*</span></label>
                                            <input type="date" id="order_date" name="order_date" required
                                                value="{{ old('order_date', isset($order) ? $order->order_date->format('Y-m-d') : date('Y-m-d')) }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="main_days_allocated" class="text-sm font-medium leading-none">Opened Orders (days) <span class="text-red-500">*</span></label>
                                            <input type="number" name="main_days_allocated" id="main_days_allocated" required value="{{ old('main_days_allocated') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2">
                                            <label for="extra_days_allocated" class="text-sm font-medium leading-none">Extended Orders (days) <span class="text-red-500">*</span></label>
                                            <input type="number" name="extra_days_allocated" id="extra_days_allocated" required value="{{ old('extra_days_allocated') }}"
                                                class="admin-input">
                                        </div>

                                        <div class="space-y-2 md:col-span-2">
                                            <label for="image_path" class="text-sm font-medium leading-none">Product Images (Paste Ctrl+V supported)</label>

                                            <div class="relative border-2 border-dashed border-border rounded-xl p-6 hover:bg-muted/50 transition ease-in-out duration-150 text-center" id="paste_area">

                                                <input id="image_path" name="image_path[]" type="file" accept="image/*" multiple class="hidden" onchange="previewImage(this)">

                                                <div id="preview_container" class="hidden flex-col items-center">
                                                    <div id="preview_list" class="flex flex-wrap justify-center gap-2 mb-3"></div>
                                                    <button type="button" onclick="removeImage()" class="text-xs text-red-500 hover:text-red-700 underline">Remove Images</button>
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

                        <div class="flex justify-end">
                            <a href="{{ route('admin.orders.index') }}"
                                class="admin-btn-secondary mr-2">
                                Close
                            </a>
                            <button type="submit"
                                class="admin-btn-primary">
                                Save Order
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function quickPasteOrderImport() {
            return {
                showImporter: false,
                rawText: '',
                feedback: {
                    type: '',
                    message: '',
                },
                fieldTargets: {
                    name: ['customer_name', 'name'],
                    email: ['email', 'mail'],
                    phone: ['phone', 'tel', 'telephone'],
                    vat: ['vat'],
                    price: ['price', 'product_price', 'total_price'],
                    size: ['size'],
                    color: ['color', 'colour'],
                    quantity: ['quantity', 'qty'],
                    country: ['country'],
                    product_link: ['product_link', 'link', 'product_url', 'url'],
                    niche: ['niche_id', 'niche', 'product_name'],
                    product_cost: ['product_cost'],
                    shipping_cost: ['shipping_cost', 'shipping_price', 'delivery_price'],
                    discount_percent: ['discount_percent', 'discount'],
                    note: ['note'],
                    store: ['store_id', 'store'],
                    supplier: ['supplier_id', 'supplier'],
                    order_date: ['order_date', 'date_order', 'date'],
                    opened_days: ['main_days_allocated'],
                    extended_days: ['extra_days_allocated'],
                },
                keyAliases: {
                    name: ['name', 'customer name'],
                    email: ['email', 'mail'],
                    phone: ['phone', 'tel', 'telephone'],
                    vat: ['vat'],
                    price: ['price', 'product price', 'total price'],
                    size: ['size'],
                    color: ['color', 'colour'],
                    quantity: ['quantity', 'qty'],
                    country: ['country'],
                    product_link: ['product link', 'link', 'url'],
                    niche: ['niche', 'product name'],
                    product_cost: ['product cost', 'buying price', 'cost price', 'purchase price'],
                    shipping_cost: ['shipping cost', 'shipping price', 'delivery price'],
                    discount_percent: ['discount', 'discount %', 'discount percent'],
                    note: ['note', 'instructions'],
                    store: ['store', 'shop'],
                    supplier: ['supplier', 'vendor'],
                    order_date: ['date order', 'order date', 'date'],
                    opened_days: ['opened orders days', 'opened order days', 'main days allocated'],
                    extended_days: ['extended orders days', 'extended order days', 'extra days allocated'],
                },
                keyLabels: {
                    name: 'Customer Name',
                    email: 'Email',
                    phone: 'Phone',
                    vat: 'VAT',
                    price: 'Price',
                    size: 'Size',
                    color: 'Color',
                    quantity: 'Quantity',
                    country: 'Country',
                    product_link: 'Product Link',
                    niche: 'Niche',
                    product_cost: 'Product Cost',
                    shipping_cost: 'Shipping Cost',
                    discount_percent: 'Discount (%)',
                    note: 'Note',
                    store: 'Store',
                    supplier: 'Supplier',
                    order_date: 'Order Date',
                    opened_days: 'Opened Orders (days)',
                    extended_days: 'Extended Orders (days)',
                },
                pasteSample() {
                    this.rawText = `*Name* : TAOUFIK EL BOUAIDI
*Email* : tuf@100kends.com
*Phone* : 00215120

*VAT* : this is a vat number
*Price* : 1001$
*size* : 35 cm
*Color* : bronze
*Quantity* : 1
*Country* : Morocco
*Product Link* : 100kends.com
*Product name* : lamp
*Product Cost* : 50$
*Shipping Cost* : 40$
*Discount %* : 10%

*Note* : 3afaaak
*Store* : 100kends
*Supplier* : TAOUFIK RUGS
*DATE ORDER*: Today
*Opened Orders (days)* : 7
*Extended Orders (days)* : 21`;
                    this.feedback = {
                        type: '',
                        message: '',
                    };
                },
                clearImporter() {
                    this.rawText = '';
                    this.feedback = {
                        type: '',
                        message: '',
                    };
                },
                normalizeToken(value) {
                    const normalized = value
                        .toLowerCase()
                        .replace(/[*_`]/g, ' ')
                        .replace(/[^a-z0-9 ]+/g, ' ')
                        .replace(/\s+/g, ' ')
                        .trim();

                    return {
                        spaced: normalized,
                        compact: normalized.replace(/\s+/g, ''),
                    };
                },
                resolveCanonicalKey(rawKey) {
                    const candidate = this.normalizeToken(rawKey);

                    for (const [canonical, aliases] of Object.entries(this.keyAliases)) {
                        for (const alias of aliases) {
                            const aliasToken = this.normalizeToken(alias);
                            if (
                                candidate.spaced === aliasToken.spaced ||
                                candidate.compact === aliasToken.compact
                            ) {
                                return canonical;
                            }
                        }
                    }

                    return null;
                },
                parseRawText(raw) {
                    const parsed = {};
                    const lines = raw.split(/\r?\n/);

                    for (const line of lines) {
                        const trimmed = line.trim();
                        if (!trimmed) {
                            continue;
                        }

                        const separatorIndex = trimmed.indexOf(':');
                        if (separatorIndex === -1) {
                            continue;
                        }

                        const rawKey = trimmed.slice(0, separatorIndex).trim();
                        const rawValue = trimmed.slice(separatorIndex + 1).trim();

                        if (!rawKey || !rawValue) {
                            continue;
                        }

                        const canonical = this.resolveCanonicalKey(rawKey);
                        if (!canonical) {
                            continue;
                        }

                        parsed[canonical] = rawValue;
                    }

                    return parsed;
                },
                findFormField(canonicalKey, form) {
                    const targets = this.fieldTargets[canonicalKey] || [];
                    for (const target of targets) {
                        const byId = form.querySelector(`#${target}`);
                        if (byId) {
                            return byId;
                        }

                        const byName = form.querySelector(`[name="${target}"]`);
                        if (byName) {
                            return byName;
                        }

                        const byArrayName = form.querySelector(`[name="${target}[]"]`);
                        if (byArrayName) {
                            return byArrayName;
                        }
                    }

                    return null;
                },
                extractNumber(rawValue, integerOnly = false) {
                    let cleaned = rawValue.replace(/[^\d.,-]/g, '').trim();
                    if (!cleaned) {
                        return null;
                    }

                    if (cleaned.includes(',') && cleaned.includes('.')) {
                        cleaned = cleaned.replace(/,/g, '');
                    } else if (cleaned.includes(',') && !cleaned.includes('.')) {
                        cleaned = cleaned.replace(/,/g, '.');
                    }

                    const parsed = integerOnly ? parseInt(cleaned, 10) : parseFloat(cleaned);
                    return Number.isFinite(parsed) ? parsed : null;
                },
                parseDateValue(rawValue) {
                    const text = rawValue.trim().toLowerCase();
                    if (!text) {
                        return null;
                    }

                    if (text === 'today') {
                        return new Date().toISOString().slice(0, 10);
                    }
                    if (text === 'tomorrow') {
                        const date = new Date();
                        date.setDate(date.getDate() + 1);
                        return date.toISOString().slice(0, 10);
                    }
                    if (text === 'yesterday') {
                        const date = new Date();
                        date.setDate(date.getDate() - 1);
                        return date.toISOString().slice(0, 10);
                    }

                    const normalized = rawValue.trim().replace(/\./g, '/').replace(/-/g, '/');
                    const slashParts = normalized.split('/').map(part => part.trim()).filter(Boolean);
                    if (slashParts.length === 3) {
                        let day = slashParts[0];
                        let month = slashParts[1];
                        let year = slashParts[2];

                        if (year.length === 2) {
                            year = `20${year}`;
                        }

                        if (day.length === 1) day = `0${day}`;
                        if (month.length === 1) month = `0${month}`;

                        const candidate = `${year}-${month}-${day}`;
                        if (!Number.isNaN(Date.parse(candidate))) {
                            return candidate;
                        }
                    }

                    if (!Number.isNaN(Date.parse(rawValue))) {
                        return new Date(rawValue).toISOString().slice(0, 10);
                    }

                    return null;
                },
                fillSelectField(field, rawValue) {
                    const target = rawValue.trim().toLowerCase();
                    if (!target) {
                        return false;
                    }

                    const options = Array.from(field.options || []);
                    if (options.length === 0) {
                        return false;
                    }

                    const exactTextMatch = options.find(option => option.textContent.trim().toLowerCase() === target);
                    if (exactTextMatch) {
                        field.value = exactTextMatch.value;
                        return true;
                    }

                    const containsTextMatch = options.find(option => option.textContent.trim().toLowerCase().includes(target));
                    if (containsTextMatch) {
                        field.value = containsTextMatch.value;
                        return true;
                    }

                    const exactValueMatch = options.find(option => String(option.value).toLowerCase() === target);
                    if (exactValueMatch) {
                        field.value = exactValueMatch.value;
                        return true;
                    }

                    return false;
                },
                valueForField(canonicalKey, rawValue, field) {
                    const value = rawValue.trim();

                    if (canonicalKey === 'quantity' || canonicalKey === 'opened_days' || canonicalKey === 'extended_days') {
                        const numeric = this.extractNumber(value, true);
                        if (numeric !== null) {
                            return String(numeric);
                        }
                        return value;
                    }

                    if (canonicalKey === 'order_date') {
                        const parsedDate = this.parseDateValue(value);
                        return parsedDate || value;
                    }

                    if (field.type === 'number') {
                        const numeric = this.extractNumber(value, false);
                        if (numeric !== null) {
                            return String(numeric);
                        }
                    }

                    return value;
                },
                parseAndFill() {
                    const raw = this.rawText.trim();
                    if (!raw) {
                        this.feedback = {
                            type: 'error',
                            message: 'Please paste order details before parsing.',
                        };
                        return;
                    }

                    const parsed = this.parseRawText(raw);
                    const parsedEntries = Object.entries(parsed);

                    if (parsedEntries.length === 0) {
                        this.feedback = {
                            type: 'error',
                            message: 'No valid key:value pairs were detected. Check formatting and field names.',
                        };
                        return;
                    }

                    const form = this.$root.querySelector('[data-order-create-form]');
                    if (!form) {
                        this.feedback = {
                            type: 'error',
                            message: 'Order form not found on the page.',
                        };
                        return;
                    }

                    const filledLabels = [];
                    const unmappedLabels = [];

                    for (const [canonicalKey, rawValue] of parsedEntries) {
                        const field = this.findFormField(canonicalKey, form);
                        if (!field) {
                            unmappedLabels.push(this.keyLabels[canonicalKey] || canonicalKey);
                            continue;
                        }

                        const finalValue = this.valueForField(canonicalKey, rawValue, field);
                        if (field.tagName === 'SELECT') {
                            const selectWasFilled = this.fillSelectField(field, finalValue);
                            if (!selectWasFilled) {
                                unmappedLabels.push(`${this.keyLabels[canonicalKey] || canonicalKey} (${rawValue.trim()})`);
                                continue;
                            }
                        } else {
                            field.value = finalValue;
                        }
                        field.dispatchEvent(new Event('input', { bubbles: true }));
                        field.dispatchEvent(new Event('change', { bubbles: true }));
                        filledLabels.push(this.keyLabels[canonicalKey] || canonicalKey);
                    }

                    if (filledLabels.length === 0) {
                        this.feedback = {
                            type: 'error',
                            message: `Recognized data found, but no matching form fields exist here. Unsupported fields: ${unmappedLabels.join(', ')}.`,
                        };
                        return;
                    }

                    if (unmappedLabels.length > 0) {
                        this.feedback = {
                            type: 'warning',
                            message: `Filled ${filledLabels.length} field(s): ${filledLabels.join(', ')}. Not present in this form: ${unmappedLabels.join(', ')}.`,
                        };
                        return;
                    }

                    this.feedback = {
                        type: 'success',
                        message: `Success: ${filledLabels.length} field(s) auto-filled.`,
                    };
                },
            };
        }
    </script>
</x-app-layout>
