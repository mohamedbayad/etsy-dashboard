<?php

namespace App\Services\Calculations;

use App\Models\Niche;
use App\Services\GoogleSheets\SheetReferenceService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class NicheSheetService
{
    public function __construct(private readonly SheetReferenceService $sheetReferenceService)
    {
    }

    public function fetchPricingRows(Niche $niche): array
    {
        if (!$niche->sheet_id) {
            return $this->result(false, 'missing_sheet', [], 'Niche has no linked sheet ID.');
        }

        if (!$this->sheetReferenceService->isValidSheetId($niche->sheet_id)) {
            return $this->result(false, 'invalid_sheet_id', [], 'Niche sheet ID is malformed.');
        }

        $sheetId = $niche->sheet_id;
        $endpoints = [
            "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv",
            "https://docs.google.com/spreadsheets/d/{$sheetId}/gviz/tq?tqx=out:csv",
        ];

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::timeout(10)->get($endpoint);

                if ($response->status() === 404) {
                    return $this->result(false, 'invalid_sheet_id', [], 'Niche sheet was not found (404).');
                }

                if (in_array($response->status(), [401, 403], true)) {
                    return $this->result(false, 'private_sheet', [], 'Niche sheet is private or requires authentication.');
                }

                if ($response->status() !== 200) {
                    continue;
                }

                $rows = $this->parseCsvRows($response->body());
                if (!empty($rows)) {
                    return $this->result(true, null, $rows, null);
                }
            } catch (Throwable) {
                continue;
            }
        }

        return $this->result(false, 'sheet_unreachable', [], 'Unable to read niche sheet via public CSV endpoints.');
    }

    public function normalizeSize(string $value): string
    {
        $value = Str::lower(trim($value));
        return preg_replace('/\s+/', '', $value) ?? '';
    }

    public function convertDhPriceToUsd(float $priceInDh): float
    {
        return round($priceInDh / 10, 2);
    }

    public function parsePriceToFloat(string $raw): ?float
    {
        $normalized = preg_replace('/[^0-9,\.\-]/', '', trim($raw));
        if ($normalized === null || $normalized === '') {
            return null;
        }

        if (Str::contains($normalized, ',') && Str::contains($normalized, '.')) {
            $normalized = str_replace(',', '', $normalized);
        } elseif (Str::contains($normalized, ',') && !Str::contains($normalized, '.')) {
            $normalized = str_replace(',', '.', $normalized);
        }

        if (!is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    private function parseCsvRows(string $csv): array
    {
        $csv = trim($csv);
        if ($csv === '') {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $csv) ?: [];
        if (empty($lines)) {
            return [];
        }

        $header = null;
        $sizeIndex = null;
        $priceIndex = null;
        $nameIndex = null;
        $rows = [];

        foreach ($lines as $lineIndex => $line) {
            $columns = str_getcsv($line);
            if (!is_array($columns) || empty(array_filter($columns, fn ($v) => trim((string) $v) !== ''))) {
                continue;
            }

            if ($header === null) {
                $header = array_map(function ($value) {
                    $value = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value) ?? (string) $value;
                    $value = Str::lower(trim($value));
                    $value = str_replace(['_', '-'], ' ', $value);
                    return preg_replace('/\s+/', ' ', $value) ?? $value;
                }, $columns);

                foreach ($header as $index => $name) {
                    if ($sizeIndex === null && Str::contains($name, 'size')) {
                        $sizeIndex = $index;
                    }
                    if ($priceIndex === null && (Str::contains($name, 'price') || Str::contains($name, 'cost'))) {
                        $priceIndex = $index;
                    }
                    if ($nameIndex === null && Str::contains($name, 'name')) {
                        $nameIndex = $index;
                    }
                }

                continue;
            }

            if ($sizeIndex === null || $priceIndex === null) {
                continue;
            }

            $size = isset($columns[$sizeIndex]) ? trim((string) $columns[$sizeIndex]) : '';
            $price = isset($columns[$priceIndex]) ? trim((string) $columns[$priceIndex]) : '';
            $name = $nameIndex !== null && isset($columns[$nameIndex]) ? trim((string) $columns[$nameIndex]) : '';

            if ($size === '' && $price === '') {
                continue;
            }

            $rows[] = [
                'row' => $lineIndex + 1,
                'name' => $name,
                'size' => $size,
                'price' => $price,
            ];
        }

        return $rows;
    }

    private function result(bool $ok, ?string $errorType, array $rows, ?string $message): array
    {
        return [
            'ok' => $ok,
            'error_type' => $errorType,
            'rows' => $rows,
            'message' => $message,
        ];
    }
}

