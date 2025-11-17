<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-foreground leading-tight">
                {{ __('Gérer les Utilisateurs') }}
            </h2>

            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                Ajouter Utilisateur
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-xl border bg-card text-card-foreground shadow">
                <div class="p-6">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Nom</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Role</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Spécialité</th>
                                    <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                @foreach ($users as $user)
                                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">

                                        <td class="p-4 align-middle font-medium">{{ $user->name }}</td>

                                        <td class="p-4 align-middle text-muted-foreground">{{ $user->email }}</td>

                                        <td class="p-4 align-middle">
                                            @if($user->role == 'admin')
                                                <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-primary text-primary-foreground">
                                                    Admin
                                                </div>
                                            @else
                                                <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-transparent bg-secondary text-secondary-foreground">
                                                    Supplier
                                                </div>
                                            @endif
                                        </td>

                                        <td class="p-4 align-middle text-muted-foreground">
                                            {{ $user->SupplierProfile->specialty ?? 'N/A' }}
                                        </td>

                                        <td class="p-4 align-middle">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors border border-input bg-background hover:bg-accent h-9 px-3">
                                                    Edit
                                                </a>

                                                @if (auth()->id() != $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Wash sure bghiti tms7?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors bg-destructive text-destructive-foreground hover:bg-destructive/90 h-9 px-3">
                                                        Delete
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
