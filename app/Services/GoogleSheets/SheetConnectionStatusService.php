<?php

namespace App\Services\GoogleSheets;

use App\Models\Niche;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class SheetConnectionStatusService
{
    private const ERROR_INVALID_URL = 'invalid_url';
    private const ERROR_INVALID_SHEET_ID = 'invalid_sheet_id';
    private const ERROR_PUBLIC_UNREACHABLE = 'public_unreachable';
    private const ERROR_PRIVATE_NOT_SHARED = 'private_not_shared';
    private const ERROR_API_AUTH_REQUIRED = 'api_auth_required';
    private const ERROR_UNKNOWN = 'unknown';

    public function __construct(private readonly SheetReferenceService $sheetReferenceService)
    {
    }

    public function evaluate(?string $sheetUrlInput, ?string $sheetIdInput, bool $tryRemote = true): array
    {
        $normalized = $this->sheetReferenceService->normalize($sheetUrlInput, $sheetIdInput);
        $sheetUrl = $normalized['sheet_url'];
        $sheetId = $normalized['sheet_id'];
        $checkedAt = Carbon::now();

        if ($sheetUrl === null && $sheetId === null) {
            return $this->result(
                status: Niche::STATUS_MISSING,
                errorMessage: 'No Sheet URL or Sheet ID provided.',
                checkedAt: $checkedAt,
                sheetUrl: null,
                sheetId: null,
                errorType: self::ERROR_UNKNOWN,
            );
        }

        if ($sheetUrl !== null && !$this->sheetReferenceService->looksLikeGoogleSheetUrl($sheetUrl)) {
            return $this->result(
                status: Niche::STATUS_INVALID,
                errorMessage: 'Invalid URL: Sheet URL must be a valid Google Sheets URL.',
                checkedAt: $checkedAt,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: self::ERROR_INVALID_URL,
            );
        }

        if ($sheetId === null || !$this->sheetReferenceService->isValidSheetId($sheetId)) {
            return $this->result(
                status: Niche::STATUS_INVALID,
                errorMessage: 'Invalid sheet ID: missing or malformed.',
                checkedAt: $checkedAt,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: self::ERROR_INVALID_SHEET_ID,
            );
        }

        if ($sheetUrl === null) {
            $sheetUrl = $this->sheetReferenceService->buildGoogleSheetUrlFromId($sheetId);
        }

        if (!$tryRemote) {
            return $this->result(
                status: Niche::STATUS_UNCHECKED,
                errorMessage: null,
                checkedAt: null,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: null,
            );
        }

        $publicProbe = $this->probePublicCsvEndpoints($sheetId);
        if ($publicProbe['is_connected'] === true) {
            return $this->result(
                status: Niche::STATUS_CONNECTED,
                errorMessage: null,
                checkedAt: $checkedAt,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: null,
            );
        }

        if ($publicProbe['error_type'] === self::ERROR_INVALID_SHEET_ID) {
            return $this->result(
                status: Niche::STATUS_INVALID,
                errorMessage: 'Invalid sheet ID: Google returned not found (404).',
                checkedAt: $checkedAt,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: self::ERROR_INVALID_SHEET_ID,
            );
        }

        if (
            $publicProbe['error_type'] === self::ERROR_PRIVATE_NOT_SHARED
            && $this->shouldAttemptAuthenticatedProbe()
        ) {
            $authProbe = $this->probeAuthenticatedApiEndpoint($sheetId);

            if ($authProbe['is_connected'] === true) {
                return $this->result(
                    status: Niche::STATUS_CONNECTED,
                    errorMessage: null,
                    checkedAt: $checkedAt,
                    sheetUrl: $sheetUrl,
                    sheetId: $sheetId,
                    errorType: null,
                );
            }

            if ($authProbe['error_type'] === self::ERROR_API_AUTH_REQUIRED) {
                return $this->result(
                    status: Niche::STATUS_UNREACHABLE,
                    errorMessage: 'API auth required: public endpoints are not accessible and authenticated Sheets API credentials are missing/unauthorized.',
                    checkedAt: $checkedAt,
                    sheetUrl: $sheetUrl,
                    sheetId: $sheetId,
                    errorType: self::ERROR_API_AUTH_REQUIRED,
                );
            }

            if ($authProbe['error_type'] === self::ERROR_INVALID_SHEET_ID) {
                return $this->result(
                    status: Niche::STATUS_INVALID,
                    errorMessage: 'Invalid sheet ID: authenticated API returned not found (404).',
                    checkedAt: $checkedAt,
                    sheetUrl: $sheetUrl,
                    sheetId: $sheetId,
                    errorType: self::ERROR_INVALID_SHEET_ID,
                );
            }
        }

        if ($publicProbe['error_type'] === self::ERROR_PRIVATE_NOT_SHARED) {
            return $this->result(
                status: Niche::STATUS_UNREACHABLE,
                errorMessage: 'Private / not publicly shared: public CSV endpoints denied access.',
                checkedAt: $checkedAt,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: self::ERROR_PRIVATE_NOT_SHARED,
            );
        }

        if ($publicProbe['error_type'] === self::ERROR_PUBLIC_UNREACHABLE) {
            return $this->result(
                status: Niche::STATUS_UNREACHABLE,
                errorMessage: 'Public but unreachable: unable to fetch public CSV endpoints.',
                checkedAt: $checkedAt,
                sheetUrl: $sheetUrl,
                sheetId: $sheetId,
                errorType: self::ERROR_PUBLIC_UNREACHABLE,
            );
        }

        return $this->result(
            status: Niche::STATUS_ERROR,
            errorMessage: 'Connection test failed: unexpected Google Sheets response classification.',
            checkedAt: $checkedAt,
            sheetUrl: $sheetUrl,
            sheetId: $sheetId,
            errorType: self::ERROR_UNKNOWN,
        );
    }

    private function probePublicCsvEndpoints(string $sheetId): array
    {
        $endpoints = [
            "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv",
            "https://docs.google.com/spreadsheets/d/{$sheetId}/gviz/tq?tqx=out:csv",
        ];

        $sawNotFound = false;
        $sawPrivate = false;
        $sawUnreachable = false;

        foreach ($endpoints as $endpoint) {
            try {
                $response = Http::timeout(8)->get($endpoint);

                if ($this->isSuccessfulCsvResponse($response)) {
                    return ['is_connected' => true, 'error_type' => null];
                }

                $statusCode = $response->status();
                if ($statusCode === 404) {
                    $sawNotFound = true;
                    continue;
                }

                if ($this->isPrivateOrAuthResponse($response)) {
                    $sawPrivate = true;
                    continue;
                }

                $sawUnreachable = true;
            } catch (Throwable) {
                $sawUnreachable = true;
            }
        }

        if ($sawNotFound) {
            return ['is_connected' => false, 'error_type' => self::ERROR_INVALID_SHEET_ID];
        }

        if ($sawPrivate) {
            return ['is_connected' => false, 'error_type' => self::ERROR_PRIVATE_NOT_SHARED];
        }

        if ($sawUnreachable) {
            return ['is_connected' => false, 'error_type' => self::ERROR_PUBLIC_UNREACHABLE];
        }

        return ['is_connected' => false, 'error_type' => self::ERROR_UNKNOWN];
    }

    private function isSuccessfulCsvResponse(Response $response): bool
    {
        if ($response->status() !== 200) {
            return false;
        }

        $contentType = strtolower($response->header('Content-Type', ''));
        $bodyPreview = strtolower(substr($response->body(), 0, 2000));

        if (Str::contains($contentType, 'text/html') && $this->looksLikeGoogleSignInPage($bodyPreview)) {
            return false;
        }

        return true;
    }

    private function isPrivateOrAuthResponse(Response $response): bool
    {
        if (in_array($response->status(), [401, 403], true)) {
            return true;
        }

        if ($response->status() !== 200) {
            return false;
        }

        $contentType = strtolower($response->header('Content-Type', ''));
        $bodyPreview = strtolower(substr($response->body(), 0, 2000));

        return Str::contains($contentType, 'text/html') && $this->looksLikeGoogleSignInPage($bodyPreview);
    }

    private function looksLikeGoogleSignInPage(string $bodyPreview): bool
    {
        return Str::contains($bodyPreview, [
            'accounts.google.com',
            'servicelogin',
            '<title>sign in',
            'to continue to google sheets',
        ]);
    }

    private function shouldAttemptAuthenticatedProbe(): bool
    {
        $apiKey = config('services.google_sheets.api_key');
        $accessToken = config('services.google_sheets.access_token');

        return is_string($apiKey) && $apiKey !== ''
            || is_string($accessToken) && $accessToken !== '';
    }

    private function probeAuthenticatedApiEndpoint(string $sheetId): array
    {
        $accessToken = config('services.google_sheets.access_token');
        $apiKey = config('services.google_sheets.api_key');

        $url = "https://sheets.googleapis.com/v4/spreadsheets/{$sheetId}?fields=spreadsheetId";
        if (is_string($apiKey) && $apiKey !== '') {
            $url .= '&key=' . urlencode($apiKey);
        }

        try {
            $request = Http::timeout(8);
            if (is_string($accessToken) && $accessToken !== '') {
                $request = $request->withToken($accessToken);
            }

            $response = $request->get($url);
            $statusCode = $response->status();

            if ($statusCode === 200) {
                return ['is_connected' => true, 'error_type' => null];
            }

            if ($statusCode === 404) {
                return ['is_connected' => false, 'error_type' => self::ERROR_INVALID_SHEET_ID];
            }

            if (in_array($statusCode, [401, 403], true)) {
                return ['is_connected' => false, 'error_type' => self::ERROR_API_AUTH_REQUIRED];
            }
        } catch (Throwable) {
            return ['is_connected' => false, 'error_type' => self::ERROR_PUBLIC_UNREACHABLE];
        }

        return ['is_connected' => false, 'error_type' => self::ERROR_UNKNOWN];
    }

    private function result(
        string $status,
        ?string $errorMessage,
        ?Carbon $checkedAt,
        ?string $sheetUrl,
        ?string $sheetId,
        ?string $errorType
    ): array {
        return [
            'status' => $status,
            'error_message' => $errorMessage,
            'checked_at' => $checkedAt,
            'sheet_url' => $sheetUrl,
            'sheet_id' => $sheetId,
            'error_type' => $errorType,
        ];
    }
}
