<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Ajouter un Nouveau Utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" x-data="{ role: 'supplier' }">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6 space-y-6">

                    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="space-y-2">
                            <label for="role" class="text-sm font-medium leading-none">Role</label>
                            <select id="role" name="role" x-model="role"
                                    class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                <option value="supplier">Supplier</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 gap-6" x-show="role === 'admin'" x-transition>
                            <div class="space-y-2">
                                <label for="name" class="text-sm font-medium leading-none">Nom (Admin)</label>
                                <input type="text" name="name" id="name"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="role === 'supplier'" x-transition>
                            <div class="space-y-2">
                                <label for="first_name" class="text-sm font-medium leading-none">Prénom (Supplier)</label>
                                <input type="text" name="first_name" id="first_name"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="space-y-2">
                                <label for="last_name" class="text-sm font-medium leading-none">Nom (Supplier)</label>
                                <input type="text" name="last_name" id="last_name"
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label for="specialty" class="text-sm font-medium leading-none">Spécialité</label>
                                <input type="text" name="specialty" id="specialty" placeholder="Ex: Jald, Zerabi..."
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                        </div>

                        <div class="border-t border-border pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium leading-none">Email</label>
                                <input type="email" name="email" id="email" required
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="space-y-2">
                                <label for="password" class="text-sm font-medium leading-none">Mot de passe</label>
                                <input type="password" name="password" id="password" required
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                            <div class="space-y-2">
                                <label for="password_confirmation" class="text-sm font-medium leading-none">Confirmer Mot de passe</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                       class="flex h-10 w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm ring-offset-background">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
