<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                Edit User: {{ $user->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border bg-card text-card-foreground shadow-sm sm:rounded-lg">
                <div class="space-y-6 p-8">

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium text-foreground">Name</label>
                                <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium text-foreground">Email</label>
                                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium text-foreground">New Password (Optional)</label>
                                <input type="text" name="password" id="password" placeholder="Leave empty to keep current password"
                                       class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>

                            <div class="space-y-2">
                                <label for="role" class="text-sm font-medium text-foreground">Role</label>
                                <select name="role" id="role" required onchange="toggleStoreSection()"
                                        class="flex h-12 w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Store Manager)</option>
                                    <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="supplier" {{ $user->role === 'supplier' ? 'selected' : '' }}>Supplier</option>
                                </select>
                            </div>

                        </div>

                        <div id="stores_section" class="mt-6 space-y-4 rounded-lg border border-border bg-muted/60 p-6" style="display: none;">
                            <label class="mb-3 block text-sm font-semibold text-foreground">Assign Stores (Select multiple)</label>

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($stores as $store)
                                    <div class="flex items-center space-x-3 rounded-md border border-border bg-background/60 p-3">
                                        <input type="checkbox" name="stores[]" value="{{ $store->id }}" id="store_{{ $store->id }}"
                                               class="h-5 w-5 rounded-md border-input bg-background text-primary focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-background"

                                            {{-- If the user already manages this store, keep it checked --}}
                                            {{ $user->stores->contains($store->id) ? 'checked' : '' }}
                                        >
                                        <label for="store_{{ $store->id }}" class="text-sm font-medium text-foreground">
                                            {{ $store->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4">
                            <a href="{{ route('admin.users.index') }}" class="rounded-md border border-border bg-muted px-4 py-2 text-sm font-medium text-foreground transition hover:bg-muted/80">Cancel</a>
                            <button type="submit" class="rounded-md bg-primary px-6 py-2 text-sm font-semibold text-primary-foreground transition hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">Update User</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleStoreSection() {
            const role = document.getElementById('role').value;
            const section = document.getElementById('stores_section');

            if (role === 'admin') {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            toggleStoreSection();
        });
    </script>
</x-app-layout>
