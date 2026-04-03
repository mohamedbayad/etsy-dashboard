<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-foreground leading-tight">
            {{ __('Create Niche') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="admin-panel">
                <div class="admin-panel-body space-y-6">
                    <form action="{{ route('admin.niches.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <label for="name" class="admin-label">Niche Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                placeholder="Example: Lamps, Poufs..."
                                class="admin-input">
                        </div>

                        <div class="space-y-2">
                            <label for="sheet_url" class="admin-label">Google Sheet URL</label>
                            <input type="text" id="sheet_url" name="sheet_url" value="{{ old('sheet_url') }}"
                                placeholder="https://docs.google.com/spreadsheets/d/XXXXXXXXXXXX/edit"
                                class="admin-input">
                            <p class="text-xs text-muted-foreground">Use a Google Sheets URL only. If URL is valid, Sheet ID is extracted automatically.</p>
                        </div>

                        <div class="space-y-2">
                            <label for="sheet_id" class="admin-label">Google Sheet ID</label>
                            <input type="text" id="sheet_id" name="sheet_id" value="{{ old('sheet_id') }}"
                                placeholder="XXXXXXXXXXXX"
                                class="admin-input">
                        </div>

                        <div class="rounded-md border border-border bg-muted/40 p-4 text-xs text-muted-foreground">
                            Future data shape from each niche sheet:
                            <span class="font-medium text-foreground">name | size | price</span>
                        </div>

                        <div class="rounded-md border border-border bg-muted/40 p-4 text-xs text-muted-foreground">
                            Connection status is checked automatically after save. You can re-test from the Niches list.
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.niches.index') }}"
                                class="admin-btn-secondary">
                                Cancel
                            </a>
                            <button type="submit"
                                class="admin-btn-primary">
                                Save Niche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
