<x-app-layout>
    <x-slot name="header">
        <div class="admin-page-header">
            <h1 class="admin-page-title">{{ __('Niche Management') }}</h1>
            <a href="{{ route('admin.niches.create') }}" class="admin-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m11 9H2"/><path d="m11 19H2"/><path d="m20 5-9 7 9 7"/><path d="M22 19V5"/></svg>
                Add Niche
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel">
                <div class="admin-panel-body">
                    <div class="admin-table-shell">
                        <table class="admin-table min-w-[1400px]">
                            <thead class="admin-table-head">
                                <tr class="admin-tr">
                                    <th class="admin-th w-[180px]">Name</th>
                                    <th class="admin-th w-[150px]">Slug</th>
                                    <th class="admin-th w-[350px]">Sheet URL</th>
                                    <th class="admin-th w-[220px]">Sheet ID</th>
                                    <th class="admin-th w-[140px]">Status</th>
                                    <th class="admin-th w-[140px]">Last Checked</th>
                                    <th class="admin-th w-[140px]">Created</th>
                                    <th class="admin-th">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0 font-medium">
                                @forelse ($niches as $niche)
                                    <tr class="admin-tr">
                                        <td class="admin-td">
                                            <div class="font-bold text-foreground text-sm">{{ $niche->name }}</div>
                                        </td>
                                        <td class="admin-td text-muted-foreground text-xs">{{ $niche->slug }}</td>
                                        <td class="admin-td">
                                            @if ($niche->sheet_url)
                                                <a
                                                    href="{{ $niche->sheet_url }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    title="{{ $niche->sheet_url }}"
                                                    class="block max-w-[320px] truncate text-xs underline hover:text-primary transition-colors"
                                                >
                                                    {{ $niche->sheet_url }}
                                                </a>
                                            @else
                                                <span class="text-muted-foreground/50 text-xs italic font-bold">N/A</span>
                                            @endif
                                        </td>
                                        <td class="admin-td">
                                            @if ($niche->sheet_id)
                                                <div
                                                    title="{{ $niche->sheet_id }}"
                                                    class="max-w-[200px] truncate font-mono text-[10px] text-muted-foreground bg-muted/30 px-2 py-1 rounded"
                                                >
                                                    {{ $niche->sheet_id }}
                                                </div>
                                            @else
                                                <span class="text-muted-foreground/50 text-xs italic font-bold">N/A</span>
                                            @endif
                                        </td>
                                        <td class="admin-td">
                                            <span class="admin-badge-success text-[10px] {{ str_contains($niche->sheet_status_badge_class, 'red') ? 'admin-badge-danger' : '' }}">
                                                {{ $niche->sheet_status_label }}
                                            </span>
                                            @if ($niche->sheet_error_message)
                                                <p class="mt-1 max-w-[120px] text-[10px] text-red-500 italic leading-tight font-bold">{{ $niche->sheet_error_message }}</p>
                                            @endif
                                        </td>
                                        <td class="admin-td text-[10px] text-muted-foreground font-bold leading-tight">
                                            {{ $niche->sheet_last_checked_at?->format('d/m/Y') }}<br/>
                                            <span class="text-[9px] opacity-60">{{ $niche->sheet_last_checked_at?->format('H:i') }}</span>
                                        </td>
                                        <td class="admin-td text-[10px] text-muted-foreground font-bold leading-tight">
                                            {{ $niche->created_at?->format('d/m/Y') }}<br/>
                                            <span class="text-[9px] opacity-60">{{ $niche->created_at?->format('H:i') }}</span>
                                        </td>
                                        <td class="admin-td">
                                            <div class="flex items-center gap-1.5">
                                                <form action="{{ route('admin.niches.test-connection', $niche->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="admin-btn-secondary-sm h-8 px-3 font-bold text-[11px] uppercase tracking-wider h-8">
                                                        Test
                                                    </button>
                                                </form>
 
                                                <a href="{{ route('admin.niches.edit', $niche->id) }}"
                                                    class="admin-btn-secondary-sm h-8 w-8 p-0 flex items-center justify-center" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                </a>
 
                                                <form action="{{ route('admin.niches.destroy', $niche->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmAdminAction(this, 'Delete Niche', 'Are you sure you want to delete this niche?', 'danger');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="admin-btn-danger-sm h-8 w-8 p-0 flex items-center justify-center" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="p-16 text-center text-muted-foreground font-bold italic">
                                            No niches registered in the database.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $niches->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
