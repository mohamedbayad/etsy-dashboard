<?php

namespace App\Services\GoogleSheets;

use Illuminate\Support\Str;

class SheetReferenceService
{
    public function normalize(?string $sheetUrlInput, ?string $sheetIdInput): array
    {
        $sheetUrl = $this->nullIfEmpty($sheetUrlInput);
        $sheetId = $this->nullIfEmpty($sheetIdInput);

        if ($sheetId !== null && $this->looksLikeUrl($sheetId)) {
            $sheetUrl = $sheetUrl ?? $sheetId;
            $sheetId = $this->extractSheetId($sheetId) ?? $sheetId;
        }

        if ($sheetUrl !== null && !Str::startsWith($sheetUrl, ['http://', 'https://'])) {
            $sheetUrl = 'https://' . ltrim($sheetUrl, '/');
        }

        if ($sheetUrl !== null) {
            $extractedSheetId = $this->extractSheetId($sheetUrl);
            if ($extractedSheetId !== null) {
                $sheetId = $sheetId ?? $extractedSheetId;
            }
        }

        if ($sheetId !== null) {
            $sheetId = preg_replace('/\s+/', '', $sheetId);
            $sheetId = $this->nullIfEmpty($sheetId);
        }

        return [
            'sheet_url' => $sheetUrl,
            'sheet_id' => $sheetId,
        ];
    }

    public function extractSheetId(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        if ($this->isValidSheetId($value)) {
            return $value;
        }

        if (!Str::startsWith($value, ['http://', 'https://']) && Str::contains($value, 'docs.google.com/')) {
            $value = 'https://' . ltrim($value, '/');
        }

        $path = parse_url($value, PHP_URL_PATH) ?? '';
        if (is_string($path) && preg_match('~/spreadsheets/d/([a-zA-Z0-9\-_]+)~', $path, $matches)) {
            return $matches[1];
        }

        $query = parse_url($value, PHP_URL_QUERY);
        if (is_string($query) && $query !== '') {
            parse_str($query, $queryParams);
            $queryId = $queryParams['id'] ?? null;
            if (is_string($queryId) && $this->isValidSheetId($queryId)) {
                return $queryId;
            }
        }

        if (preg_match('~spreadsheets/d/([a-zA-Z0-9\-_]+)~', $value, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function isValidSheetId(?string $sheetId): bool
    {
        if ($sheetId === null) {
            return false;
        }

        return preg_match('/^[a-zA-Z0-9\-_]{15,}$/', $sheetId) === 1;
    }

    public function looksLikeGoogleSheetUrl(?string $sheetUrl): bool
    {
        if ($sheetUrl === null) {
            return false;
        }

        if (filter_var($sheetUrl, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $host = strtolower((string) parse_url($sheetUrl, PHP_URL_HOST));
        $path = parse_url($sheetUrl, PHP_URL_PATH) ?? '';

        $isGoogleDocsHost = preg_match('/(^|\.)docs\.google\.com$/', $host) === 1;

        return $isGoogleDocsHost
            && is_string($path)
            && Str::contains($path, '/spreadsheets/');
    }

    public function buildGoogleSheetUrlFromId(string $sheetId): string
    {
        return "https://docs.google.com/spreadsheets/d/{$sheetId}/edit";
    }

    private function looksLikeUrl(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false || Str::contains($value, 'docs.google.com/spreadsheets');
    }

    private function nullIfEmpty(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }
}
