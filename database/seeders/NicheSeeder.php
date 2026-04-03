<?php

namespace Database\Seeders;

use App\Models\Niche;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NicheSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niches')->truncate();

        $now = Carbon::now();

        $niches = [
            [
                'name' => 'Lamp',
                'sheet_id' => '1Pl49w1SfP1w4O1eCu1rYjKPEb40Ot7eUc6k_x0bAsC4',
                'sheet_status' => Niche::STATUS_CONNECTED,
                'sheet_error_message' => null,
                'sheet_last_checked_at' => $now->copy()->subDays(1),
            ],
            [
                'name' => 'Pouf',
                'sheet_id' => '1P0ufD3m0Shee7Idz8xYvQwErTyUiOp1234567890abc',
                'sheet_status' => Niche::STATUS_CONNECTED,
                'sheet_error_message' => null,
                'sheet_last_checked_at' => $now->copy()->subDays(1),
            ],
            [
                'name' => 'Rug',
                'sheet_id' => '1RugD3m0Sh33tIdkLmNoPqRsTuVwXyZ9876543210abc',
                'sheet_status' => Niche::STATUS_UNREACHABLE,
                'sheet_error_message' => 'Public endpoint unreachable for demo data.',
                'sheet_last_checked_at' => $now->copy()->subHours(12),
            ],
            [
                'name' => 'Pillow',
                'sheet_id' => '1PilLowSh33tD3m0IdAbcDefGhijkLmNopQrStuVwx',
                'sheet_status' => Niche::STATUS_INVALID,
                'sheet_error_message' => 'Demo invalid status to test edge cases.',
                'sheet_last_checked_at' => $now->copy()->subHours(12),
            ],
        ];

        foreach ($niches as $niche) {
            Niche::create([
                'name' => $niche['name'],
                'slug' => Str::slug($niche['name']),
                'sheet_url' => "https://docs.google.com/spreadsheets/d/{$niche['sheet_id']}/edit?usp=sharing",
                'sheet_id' => $niche['sheet_id'],
                'sheet_status' => $niche['sheet_status'],
                'sheet_last_checked_at' => $niche['sheet_last_checked_at'],
                'sheet_error_message' => $niche['sheet_error_message'],
            ]);
        }
    }
}

