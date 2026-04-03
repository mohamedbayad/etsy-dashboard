<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('monthly_ads_entries')->truncate();
        DB::table('orders')->truncate();
        DB::table('niches')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('store_user')->truncate();
        DB::table('stores')->truncate();
        DB::table('users')->truncate();

        $superAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        $this->call([
            StoreSeeder::class,
            SupplierSeeder::class,
            NicheSeeder::class,
            OrderSeeder::class,
            MonthlyAdsEntrySeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Operations Admin',
            'email' => 'ops-admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $admin->stores()->sync(Store::query()->pluck('id')->all());
        $superAdmin->stores()->sync(Store::query()->pluck('id')->all());

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
