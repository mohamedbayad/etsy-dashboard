<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthlyAdsEntrySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('monthly_ads_entries')->truncate();

        $entries = [
            // January 2026: two partial entries
            ['year' => 2026, 'month' => 1, 'amount' => 120.00, 'entry_date' => '2026-01-06', 'is_full_month' => false, 'note' => 'Meta ads first half', 'created_at' => '2026-01-06 10:15:00', 'updated_at' => '2026-01-06 10:15:00'],
            ['year' => 2026, 'month' => 1, 'amount' => 165.00, 'entry_date' => '2026-01-24', 'is_full_month' => false, 'note' => 'Google ads second half', 'created_at' => '2026-01-24 16:20:00', 'updated_at' => '2026-01-24 16:20:00'],

            // February 2026: one full-month entry
            ['year' => 2026, 'month' => 2, 'amount' => 340.00, 'entry_date' => '2026-02-28', 'is_full_month' => true, 'note' => 'Final monthly ads amount', 'created_at' => '2026-02-28 19:05:00', 'updated_at' => '2026-02-28 19:05:00'],

            // March 2026: three partial entries
            ['year' => 2026, 'month' => 3, 'amount' => 95.00, 'entry_date' => '2026-03-07', 'is_full_month' => false, 'note' => 'Week 1 spend', 'created_at' => '2026-03-07 11:45:00', 'updated_at' => '2026-03-07 11:45:00'],
            ['year' => 2026, 'month' => 3, 'amount' => 110.00, 'entry_date' => '2026-03-17', 'is_full_month' => false, 'note' => 'Mid-month boost', 'created_at' => '2026-03-17 13:10:00', 'updated_at' => '2026-03-17 13:10:00'],
            ['year' => 2026, 'month' => 3, 'amount' => 130.00, 'entry_date' => '2026-03-29', 'is_full_month' => false, 'note' => 'End-of-month spend', 'created_at' => '2026-03-29 20:30:00', 'updated_at' => '2026-03-29 20:30:00'],
        ];

        DB::table('monthly_ads_entries')->insert($entries);
    }
}

