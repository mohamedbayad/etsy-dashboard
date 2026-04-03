<?php

use App\Services\GoogleSheets\SheetReferenceService;

it('extracts sheet id from shared google sheet url', function () {
    $service = new SheetReferenceService();

    $sheetId = $service->extractSheetId('https://docs.google.com/spreadsheets/d/1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4/edit?usp=sharing');

    expect($sheetId)->toBe('1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4');
});

it('normalizes sheet id from url input entered in sheet id field', function () {
    $service = new SheetReferenceService();

    $normalized = $service->normalize(null, 'docs.google.com/spreadsheets/d/1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4/edit');

    expect($normalized['sheet_id'])->toBe('1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4');
    expect($normalized['sheet_url'])->toBe('https://docs.google.com/spreadsheets/d/1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4/edit');
});
