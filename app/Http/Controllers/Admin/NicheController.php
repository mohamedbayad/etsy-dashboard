<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niche;
use App\Services\GoogleSheets\SheetConnectionStatusService;
use App\Services\GoogleSheets\SheetReferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class NicheController extends Controller
{
    public function __construct(
        private readonly SheetReferenceService $sheetReferenceService,
        private readonly SheetConnectionStatusService $sheetConnectionStatusService,
    ) {
    }

    public function index()
    {
        $niches = Niche::orderBy('name')->paginate(20);
        return view('admin.niches.index', compact('niches'));
    }

    public function create()
    {
        return view('admin.niches.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateNicheInput($request->all());
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['sheet_status'] = Niche::STATUS_UNCHECKED;

        $niche = Niche::create($validated);
        $this->refreshSheetStatus($niche, true);

        return redirect()->route('admin.niches.index')->with('success', 'Niche created successfully. Sheet status: ' . $niche->sheet_status_label . '.');
    }

    public function edit(Niche $niche)
    {
        return view('admin.niches.edit', compact('niche'));
    }

    public function update(Request $request, Niche $niche)
    {
        $validated = $this->validateNicheInput($request->all(), $niche->id);
        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $niche->id);

        $niche->update($validated);
        $this->refreshSheetStatus($niche, true);

        return redirect()->route('admin.niches.index')->with('success', 'Niche updated successfully. Sheet status: ' . $niche->sheet_status_label . '.');
    }

    public function destroy(Niche $niche)
    {
        $niche->delete();

        return redirect()->route('admin.niches.index')->with('success', 'Niche deleted successfully.');
    }

    public function testConnection(Niche $niche)
    {
        $this->refreshSheetStatus($niche, true);

        if ($niche->sheet_status === Niche::STATUS_CONNECTED) {
            return back()->with('success', 'Sheet connection successful.');
        }

        $message = 'Sheet test result: ' . $niche->sheet_status_label . '.';
        if ($niche->sheet_error_message) {
            $message .= ' ' . $niche->sheet_error_message;
        }

        return back()->with('error', $message);
    }

    private function validateNicheInput(array $payload, ?int $nicheId = null): array
    {
        $normalized = $this->sheetReferenceService->normalize(
            $payload['sheet_url'] ?? null,
            $payload['sheet_id'] ?? null
        );

        $input = [
            'name' => trim((string)($payload['name'] ?? '')),
            'sheet_url' => $normalized['sheet_url'],
            'sheet_id' => $normalized['sheet_id'],
        ];

        $validator = Validator::make($input, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('niches', 'name')->ignore($nicheId),
            ],
            'sheet_url' => [
                'nullable',
                'string',
                'max:2048',
                'url',
                'required_without:sheet_id',
            ],
            'sheet_id' => [
                'nullable',
                'string',
                'max:255',
                'required_without:sheet_url',
            ],
        ], [
            'sheet_url.required_without' => 'Provide either a Google Sheet URL or a Sheet ID.',
            'sheet_id.required_without' => 'Provide either a Google Sheet URL or a Sheet ID.',
        ]);

        $validator->after(function ($validator) use ($input) {
            if ($input['sheet_url'] !== null && !$this->sheetReferenceService->looksLikeGoogleSheetUrl($input['sheet_url'])) {
                $validator->errors()->add('sheet_url', 'Sheet URL must be a valid Google Sheets URL.');
            }

            if ($input['sheet_id'] !== null && !$this->sheetReferenceService->isValidSheetId($input['sheet_id'])) {
                $validator->errors()->add('sheet_id', 'Sheet ID format looks invalid.');
            }
        });

        return $validator->validate();
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'niche';
        }

        $slug = $base;
        $counter = 2;

        while (
            Niche::where('slug', $slug)
                ->when($ignoreId !== null, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function refreshSheetStatus(Niche $niche, bool $tryRemote): void
    {
        $result = $this->sheetConnectionStatusService->evaluate(
            $niche->sheet_url,
            $niche->sheet_id,
            $tryRemote
        );

        $niche->update([
            'sheet_url' => $result['sheet_url'],
            'sheet_id' => $result['sheet_id'],
            'sheet_status' => $result['status'],
            'sheet_last_checked_at' => $result['checked_at'],
            'sheet_error_message' => $result['error_message'],
        ]);
    }
}
