<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Store;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Nkhwiw table lowel
        DB::table('stores')->truncate();

        Store::create(['name' => 'SaharaSteps']);
        Store::create(['name' => 'Brassorae']);
    }
}
