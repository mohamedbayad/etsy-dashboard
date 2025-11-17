<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Supplierseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Nkhwiw table lowel (b 7tiram l relations)
        DB::table('suppliers')->truncate();

        // Supplier 1 (Jald)
        $user1 = User::create([
            'name' => 'Ahmed Jeldawi',
            'email' => 'ahmed@example.com',
            'password' => Hash::make('password'),
            'role' => 'supplier',
        ]);

        $user1->SupplierProfile()->create([
            'first_name' => 'Ahmed',
            'last_name' => 'Jeldawi',
            'specialty' => 'Jald',
        ]);

        // Supplier 2 (Zerabi)
        $user2 = User::create([
            'name' => 'Fatima Zerbiya',
            'email' => 'fatima@example.com',
            'password' => Hash::make('password'),
            'role' => 'supplier',
        ]);

        $user2->SupplierProfile()->create([
            'first_name' => 'Fatima',
            'last_name' => 'Zerbiya',
            'specialty' => 'Zerabi',
        ]);
    }
}
