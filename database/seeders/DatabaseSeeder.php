<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Khasn n7tarmo tartib 7it 3andna relations
        // Nqado l-database bach t9bel nkhwiw tables bla mochkil
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Nkhwiw tables dyal l-users w l-orders lowlin
        DB::table('users')->truncate();
        DB::table('orders')->truncate();

        // Hna ghadi nsaybo l-Admin dyalk
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // L-password howa 'password'
            'role' => 'admin',
        ]);

        // N3ayto 3la l-Seeders b tartib
        $this->call([
            StoreSeeder::class,
            Supplierseeder::class, // Hada ghayzid users b role 'Supplier'
            OrderSeeder::class,
        ]);

        // Nrj3o kolshi kif kan
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
