<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Modifier l\'utilisateur') }}: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6 space-y-6">

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none">Role</label>
                            <p class="px-3 py-2 text-lg font-medium text-muted-foreground">{{ ucfirst($user->role) }}</p>
                            <input type="hidden" name="role" value="{{ $user->role }}"> </div>

                        @if ($user->role == 'admin')
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium leading-none">Nom (Admin)</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                        </div>
                        @endif

                        @if ($user->role == 'Supplier')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="first_name" class="text-sm font-medium leading-none">Prénom (Supplier)</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->SupplierProfile?->first_name) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="space-y-2">
                                <label for="last_name" class="text-sm font-medium leading-none">Nom (Supplier)</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->SupplierProfile?->last_name) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label for="specialty" class="text-sm font-medium leading-none">Spécialité</label>
                                <input type="text" name="specialty" id="specialty" value="{{ old('specialty', $user->SupplierProfile?->specialty) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                        </div>
                        @endif

                        <div class="border-t border-border pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium leading-none">Email</label>
                                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-sm font-medium leading-none">Mot de passe</label>
                                <p class="text-xs text-muted-foreground">Keep it empty, if you don't change it</p>
                            </div>
                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium leading-none">New Password</label>
                                <input type="password" name="password" id="password"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="space-y-2">
                                <label for="password_confirmation" class="text-sm font-medium leading-none">New Password (Confermation)</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Mettre à Jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
