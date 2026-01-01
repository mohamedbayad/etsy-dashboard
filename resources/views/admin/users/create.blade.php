<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                Create New User
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm sm:rounded-lg">
                <div class="space-y-6 p-8">

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2 md:col-span-2">
                                <label for="role" class="text-sm font-medium text-foreground">
                                    Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role" required onchange="toggleSections()"
                                        class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="" disabled selected>Select Role...</option>
                                    <option value="admin">Admin (Store Manager)</option>
                                    <option value="supplier">Supplier</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                            </div>
                        </div>

                        <div id="supplier_identity_section" class="grid grid-cols-1 gap-6 md:grid-cols-2" style="display: none;">
                            <div class="space-y-2">
                                <label for="first_name" class="text-sm font-medium text-foreground">
                                    First Name (Supplier) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="space-y-2">
                                <label for="last_name" class="text-sm font-medium text-foreground">
                                    Last Name (Supplier) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div id="general_name_section" class="space-y-2">
                            <label for="name" class="text-sm font-medium text-foreground">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>

                        <div id="supplier_specialty_section" class="space-y-2" style="display: none;">
                            <label for="specialty" class="text-sm font-medium text-foreground">
                                Specialty
                            </label>
                            <input type="text" name="specialty" id="specialty" value="{{ old('specialty') }}" placeholder="Ex: Leather, Brass..."
                                   class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-foreground">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium text-foreground">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password" id="password" required
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="space-y-2">
                                <label for="password_confirmation" class="text-sm font-medium text-foreground">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <div id="stores_section" class="mt-6 space-y-4 rounded-lg border border-border bg-muted/60 p-6" style="display: none;">
                            <label class="text-sm font-semibold text-foreground block">
                                Assign Stores (Select multiple)
                            </label>

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                                @if(isset($stores) && count($stores) > 0)
                                    @foreach($stores as $store)
                                        <div class="flex items-center space-x-3 rounded-md border border-border bg-background/60 p-3">
                                            <input type="checkbox" name="stores[]" value="{{ $store->id }}" id="store_{{ $store->id }}"
                                                   class="h-5 w-5 rounded-md border-input bg-background text-primary focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background">
                                            <label for="store_{{ $store->id }}" class="text-sm font-medium text-foreground cursor-pointer select-none">
                                                {{ $store->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-red-500">No stores available. Please create a store first.</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <a href="{{ route('admin.users.index') }}" class="rounded-md border border-border bg-muted px-4 py-2 text-sm font-medium text-foreground transition hover:bg-muted/80">Cancel</a>
                            <button type="submit" class="rounded-md bg-primary px-6 py-2 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                Create User
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSections() {
            const role = document.getElementById('role').value;

            const storesSection = document.getElementById('stores_section');
            const supplierIdentity = document.getElementById('supplier_identity_section');
            const supplierSpecialty = document.getElementById('supplier_specialty_section');
            const generalName = document.getElementById('general_name_section');

            // Reset visibility
            storesSection.style.display = 'none';
            supplierIdentity.style.display = 'none';
            supplierSpecialty.style.display = 'none';
            generalName.style.display = 'block';

            if (role === 'admin') {
                storesSection.style.display = 'block';
            } else if (role === 'supplier') {
                supplierIdentity.style.display = 'grid';
                supplierSpecialty.style.display = 'block';
                generalName.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
