<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Edit Niche') }}: {{ $niche->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel">
                <div class="admin-panel-body space-y-6">
                    <form action="{{ route('admin.niches.update', $niche->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="space-y-2">
                            <label for="name" class="admin-label">Niche Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $niche->name) }}" required
                                class="admin-input">
                        </div>

                        <div class="space-y-2">
                            <label for="sheet_url" class="admin-label">Google Sheet URL</label>
                            <input type="text" id="sheet_url" name="sheet_url" value="{{ old('sheet_url', $niche->sheet_url) }}"
                                placeholder="https://docs.google.com/spreadsheets/d/XXXXXXXXXXXX/edit"
                                class="admin-input">
                        </div>

                        <div class="space-y-2">
                            <label for="sheet_id" class="admin-label">Google Sheet ID</label>
                            <input type="text" id="sheet_id" name="sheet_id" value="{{ old('sheet_id', $niche->sheet_id) }}"
                                class="admin-input">
                            <p class="text-xs text-muted-foreground">At least one of URL or ID is required.</p>
                        </div>

                        <div class="rounded-md border border-border bg-muted/40 p-4 text-xs text-muted-foreground space-y-2">
                            <div class="flex items-center gap-2">
                                <span>Current sheet status:</span>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $niche->sheet_status_badge_class }}">
                                    {{ $niche->sheet_status_label }}
                                </span>
                            </div>
                            <p>Last checked: {{ $niche->sheet_last_checked_at?->format('d/m/Y H:i') ?? 'Never' }}</p>
                            @if ($niche->sheet_error_message)
                                <p>Error detail: {{ $niche->sheet_error_message }}</p>
                            @endif
                            <form action="{{ route('admin.niches.test-connection', $niche->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="admin-btn-secondary-sm">
                                    Test Connection
                                </button>
                            </form>
                        </div>

                        <div class="rounded-md border border-border bg-muted/40 p-4 text-xs text-muted-foreground">
                            Slug generated from niche name:
                            <span class="font-medium text-foreground">{{ $niche->slug }}</span>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.niches.index') }}"
                                class="admin-btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                class="admin-btn-primary">
                                Update Niche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
