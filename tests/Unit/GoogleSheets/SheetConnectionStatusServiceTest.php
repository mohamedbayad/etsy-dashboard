<?php

use App\Models\Niche;
use App\Services\GoogleSheets\SheetConnectionStatusService;
use App\Services\GoogleSheets\SheetReferenceService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

it('marks sheet connected when any public csv endpoint is reachable', function () {
    Http::fake([
        'https://docs.google.com/spreadsheets/*/export?format=csv' => Http::response('Forbidden', 403),
        'https://docs.google.com/spreadsheets/*/gviz/tq?tqx=out:csv' => Http::response("name,value\nfoo,1", 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]),
    ]);

    $service = new SheetConnectionStatusService(new SheetReferenceService());
    $result = $service->evaluate(
        'https://docs.google.com/spreadsheets/d/1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4/edit?usp=sharing',
        null,
        true
    );

    expect($result['status'])->toBe(Niche::STATUS_CONNECTED);
    expect($result['error_message'])->toBeNull();
});

it('classifies inaccessible public sheet as private not publicly shared', function () {
    Config::set('services.google_sheets.api_key', null);
    Config::set('services.google_sheets.access_token', null);

    Http::fake([
        'https://docs.google.com/spreadsheets/*' => Http::response('Forbidden', 403),
    ]);

    $service = new SheetConnectionStatusService(new SheetReferenceService());
    $result = $service->evaluate(
        'https://docs.google.com/spreadsheets/d/1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4/edit',
        null,
        true
    );

    expect($result['status'])->toBe(Niche::STATUS_UNREACHABLE);
    expect($result['error_type'])->toBe('private_not_shared');
    expect($result['error_message'])->toBe('Private / not publicly shared: public CSV endpoints denied access.');
});

it('classifies api auth required only when authenticated api route is attempted', function () {
    Config::set('services.google_sheets.api_key', 'dummy-key');
    Config::set('services.google_sheets.access_token', null);

    Http::fake([
        'https://docs.google.com/spreadsheets/*' => Http::response('Forbidden', 403),
        'https://sheets.googleapis.com/v4/spreadsheets/*' => Http::response('Unauthorized', 401),
    ]);

    $service = new SheetConnectionStatusService(new SheetReferenceService());
    $result = $service->evaluate(
        'https://docs.google.com/spreadsheets/d/1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4/edit',
        null,
        true
    );

    expect($result['status'])->toBe(Niche::STATUS_UNREACHABLE);
    expect($result['error_type'])->toBe('api_auth_required');
    expect($result['error_message'])->toContain('API auth required');
});

it('classifies malformed google sheet url as invalid url', function () {
    $service = new SheetConnectionStatusService(new SheetReferenceService());
    $result = $service->evaluate('https://example.com/not-a-sheet', null, false);

    expect($result['status'])->toBe(Niche::STATUS_INVALID);
    expect($result['error_type'])->toBe('invalid_url');
    expect($result['error_message'])->toBe('Invalid URL: Sheet URL must be a valid Google Sheets URL.');
});
