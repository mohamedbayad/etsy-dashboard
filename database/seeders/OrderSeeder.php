<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Order;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('orders')->truncate();

        // Jib l-data li saybna
        $store1 = Store::where('name', 'SaharaSteps')->first();
        $store2 = Store::where('name', 'Brassorae')->first();
        $supplier1 = Supplier::where('specialty', 'Jald')->first();
        $supplier2 = Supplier::where('specialty', 'Zerabi')->first();

        // Order 1 (l Ahmed)
        Order::create([
            'store_id' => $store1->id,
            'Supplier_id' => $supplier1->id,
            'color' => 'Marron',
            'size' => '42',
            'status' => 'main_time',
            'main_days_allocated' => 5,
            'extra_days_allocated' => 2,
            'days_spent_main' => 1, // Bhal ila daz nhar
        ]);

        // Order 2 (l Fatima)
        Order::create([
            'store_id' => $store2->id,
            'Supplier_id' => $supplier2->id,
            'color' => 'Rouge/Bleu',
            'size' => '200x300',
            'status' => 'extra_time', // Order f waqt idafi
            'main_days_allocated' => 10,
            'extra_days_allocated' => 5,
            'days_spent_main' => 10, // Sala lwaqt lowel
            'days_spent_extra' => 2, // Daz yomayn f l-idafi
        ]);

        // Order 3 (l Ahmed)
        Order::create([
            'store_id' => $store1->id,
            'Supplier_id' => $supplier1->id,
            'color' => 'Noir',
            'size' => '40',
            'status' => 'main_time',
            'main_days_allocated' => 3,
            'extra_days_allocated' => 1,
            'days_spent_main' => 0,
        ]);
    }
}
