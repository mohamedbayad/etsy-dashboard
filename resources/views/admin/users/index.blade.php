<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('User Management') }}</h1>
            <a href="{{ route('admin.users.create') }}" class="admin-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="11" x2="22" y2="11"/><line x1="20.5" y1="9.5" x2="20.5" y2="12.5"/></svg>
                Add User
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel animate-fade-up">
                <div class="admin-panel-body">
                    <div class="admin-table-shell">
                        <table class="admin-table">
                            <thead class="admin-table-head">
                                <tr class="admin-tr">
                                    <th class="admin-th">FullName</th>
                                    <th class="admin-th">Email Address</th>
                                    <th class="admin-th">Security Role</th>
                                    <th class="admin-th">Specialty</th>
                                    <th class="admin-th">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0 text-foreground">
                                @foreach ($users as $user)
                                    <tr class="admin-tr group">
                                        <td class="admin-td">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary/10 text-primary font-bold text-xs ring-1 ring-primary/20">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div class="font-bold text-foreground">{{ $user->name }}</div>
                                            </div>
                                        </td>

                                        <td class="admin-td font-medium text-muted-foreground">{{ $user->email }}</td>

                                        <td class="admin-td">
                                            @if($user->role == 'admin')
                                                <span class="admin-badge-primary">Administrator</span>
                                            @elseif ($user->role == 'super_admin')
                                                <span class="admin-badge-primary bg-indigo-600/10 text-indigo-600 border-indigo-600/20 italic">Super Admin</span>
                                            @else
                                                <span class="admin-badge-secondary">Supplier Partner</span>
                                            @endif
                                        </td>

                                        <td class="admin-td font-bold text-xs">
                                            @if($user->SupplierProfile && $user->SupplierProfile->specialty)
                                                <span class="admin-badge-neutral text-[10px] bg-muted/20">
                                                    {{ $user->SupplierProfile->specialty }}
                                                </span>
                                            @else
                                                <span class="text-[10px] text-muted-foreground/50 italic font-bold uppercase tracking-wider">No Specialty</span>
                                            @endif
                                        </td>

                                        <td class="admin-td">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="admin-btn-secondary-sm h-8 w-8 p-0" title="Edit User">
                                                   <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </a>

                                                @if (auth()->id() != $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmAdminAction(this, 'Delete User', 'Are you sure you want to permanently delete this user?', 'danger');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="admin-btn-danger-sm h-8 w-8 p-0" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
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
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
