<?php

namespace Database\Seeders;

use App\Models\Niche;
use App\Models\Order;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->truncate();

        $stores = Store::orderBy('id')->get()->values();
        $suppliers = Supplier::orderBy('id')->get()->values();
        $nichesByName = Niche::query()->get()->keyBy('name');

        if ($stores->isEmpty() || $suppliers->isEmpty() || $nichesByName->isEmpty()) {
            return;
        }

        $catalog = [
            'Lamp' => [
                'sizes' => ['23 cm', '35 cm', '45 cm', '72 cm'],
                'edge_sizes' => ['18 cm'],
                'price_min' => 95,
                'price_max' => 240,
                'shipping_min' => 9,
                'shipping_max' => 24,
                'cost_map' => ['23 cm' => 42.0, '35 cm' => 50.0, '45 cm' => 67.0, '72 cm' => 96.0],
            ],
            'Pouf' => [
                'sizes' => ['Small', 'Medium', 'Large'],
                'edge_sizes' => ['XL'],
                'price_min' => 65,
                'price_max' => 160,
                'shipping_min' => 11,
                'shipping_max' => 30,
                'cost_map' => ['Small' => 24.0, 'Medium' => 33.0, 'Large' => 45.0],
            ],
            'Rug' => [
                'sizes' => ['2x3 ft', '2x6 ft', '3x5 ft', '5x8 ft'],
                'edge_sizes' => ['9x12 ft'],
                'price_min' => 145,
                'price_max' => 420,
                'shipping_min' => 20,
                'shipping_max' => 55,
                'cost_map' => ['2x3 ft' => 40.0, '2x6 ft' => 74.0, '3x5 ft' => 112.0, '5x8 ft' => 166.0],
            ],
            'Pillow' => [
                'sizes' => ['40x40 cm', '50x50 cm', '60x60 cm'],
                'edge_sizes' => ['70x70 cm'],
                'price_min' => 35,
                'price_max' => 105,
                'shipping_min' => 7,
                'shipping_max' => 16,
                'cost_map' => ['40x40 cm' => 12.0, '50x50 cm' => 16.0, '60x60 cm' => 22.0],
            ],
        ];

        $months = [
            ['year' => 2026, 'month' => 1, 'orders' => 14],
            ['year' => 2026, 'month' => 2, 'orders' => 14],
            ['year' => 2026, 'month' => 3, 'orders' => 14],
        ];

        $customers = [
            ['name' => 'Youssef Amrani', 'email' => 'youssef.amrani@example.com', 'country' => 'Morocco'],
            ['name' => 'Sara Bennani', 'email' => 'sara.bennani@example.com', 'country' => 'Morocco'],
            ['name' => 'Lina Johnson', 'email' => 'lina.johnson@example.com', 'country' => 'United States'],
            ['name' => 'Omar El Fassi', 'email' => 'omar.elfassi@example.com', 'country' => 'Morocco'],
            ['name' => 'Noah Turner', 'email' => 'noah.turner@example.com', 'country' => 'United Kingdom'],
            ['name' => 'Chloe Martin', 'email' => 'chloe.martin@example.com', 'country' => 'France'],
            ['name' => 'Aya Kabbaj', 'email' => 'aya.kabbaj@example.com', 'country' => 'Morocco'],
            ['name' => 'Ethan Walker', 'email' => 'ethan.walker@example.com', 'country' => 'Canada'],
            ['name' => 'Hajar Idrissi', 'email' => 'hajar.idrissi@example.com', 'country' => 'Morocco'],
            ['name' => 'Mia Rodriguez', 'email' => 'mia.rodriguez@example.com', 'country' => 'Spain'],
            ['name' => 'Adam Laghrari', 'email' => 'adam.laghrari@example.com', 'country' => 'Morocco'],
            ['name' => 'Leila Naciri', 'email' => 'leila.naciri@example.com', 'country' => 'Belgium'],
        ];

        $statuses = ['main_time', 'main_time', 'main_time', 'extra_time', 'completed', 'not_shipped'];
        $nicheNames = array_keys($catalog);
        $globalCounter = 0;

        foreach ($months as $monthConfig) {
            $year = $monthConfig['year'];
            $month = $monthConfig['month'];

            for ($i = 1; $i <= $monthConfig['orders']; $i++) {
                $globalCounter++;
                $nicheName = $nicheNames[($globalCounter - 1) % count($nicheNames)];
                $niche = $nichesByName->get($nicheName);
                $profile = $catalog[$nicheName];

                $sizeCase = $globalCounter % 10;
                if ($sizeCase === 0) {
                    $size = null;
                } elseif ($sizeCase === 5) {
                    $size = $profile['edge_sizes'][0];
                } else {
                    $size = $profile['sizes'][($globalCounter + $i) % count($profile['sizes'])];
                }

                $price = $this->money($profile['price_min'] + (($globalCounter * 17) % max(1, ($profile['price_max'] - $profile['price_min']))));
                $shipping = $this->money($profile['shipping_min'] + (($globalCounter * 5) % max(1, ($profile['shipping_max'] - $profile['shipping_min']))));
                $discountPercent = $this->discountPercentForIndex($globalCounter);

                $shouldSetProductCost = $globalCounter % 4 !== 0;
                $productCost = null;
                if ($shouldSetProductCost && $size !== null && isset($profile['cost_map'][$size])) {
                    $productCost = $this->money($profile['cost_map'][$size]);
                }

                if ($globalCounter % 13 === 0) {
                    $niche = null; // edge-case: missing niche
                    $productCost = null;
                }

                $status = $statuses[$globalCounter % count($statuses)];
                $mainDaysAllocated = 7 + ($globalCounter % 4);
                $extraDaysAllocated = 3 + ($globalCounter % 3);
                [$daysSpentMain, $daysSpentExtra, $daysRetarded] = $this->timingForStatus(
                    $status,
                    $mainDaysAllocated,
                    $extraDaysAllocated,
                    $globalCounter
                );

                $customer = $customers[$globalCounter % count($customers)];
                $orderDate = Carbon::create($year, $month, min(28, 1 + (($globalCounter * 2) % 28)));
                $store = $stores[($globalCounter + $month) % $stores->count()];
                $supplier = $suppliers[($globalCounter + $month + 1) % $suppliers->count()];

                Order::create([
                    'store_id' => $store->id,
                    'supplier_id' => $supplier->id,
                    'niche_id' => $niche?->id,
                    'order_date' => $orderDate->toDateString(),
                    'color' => $this->colorForNiche($nicheName, $globalCounter),
                    'size' => $size,
                    'status' => $status,
                    'main_days_allocated' => $mainDaysAllocated,
                    'extra_days_allocated' => $extraDaysAllocated,
                    'days_spent_main' => $daysSpentMain,
                    'days_spent_extra' => $daysSpentExtra,
                    'days_retarded' => $daysRetarded,
                    'note' => $this->noteForScenario($nicheName, $size, $discountPercent, $productCost),
                    'customer_name' => $customer['name'],
                    'email' => $customer['email'],
                    'country' => $customer['country'],
                    'quantity' => 1,
                    'price' => $price,
                    'shipping_cost' => $shipping,
                    'discount' => null,
                    'discount_percent' => $discountPercent,
                    'product_cost' => $productCost,
                    'created_at' => $orderDate->copy()->setTime(10 + ($globalCounter % 8), 15, 0),
                    'updated_at' => $orderDate->copy()->setTime(10 + ($globalCounter % 8), 35, 0),
                ]);
            }
        }
    }

    private function discountPercentForIndex(int $index): float
    {
        $bucket = $index % 6;
        if (in_array($bucket, [0, 1], true)) {
            return 0.0; // no discount
        }

        if (in_array($bucket, [2, 3], true)) {
            return $this->money(5 + ($index % 6)); // small discount
        }

        return $this->money(15 + (($index * 3) % 12)); // higher discount
    }

    private function timingForStatus(string $status, int $mainAllocated, int $extraAllocated, int $index): array
    {
        if ($status === 'completed') {
            return [max(1, $mainAllocated - 1), 0, 0];
        }

        if ($status === 'extra_time') {
            $daysExtra = min($extraAllocated, 1 + ($index % max(1, $extraAllocated)));
            return [$mainAllocated, $daysExtra, 0];
        }

        if ($status === 'not_shipped') {
            $daysExtra = $extraAllocated;
            $retarded = 1 + ($index % 4);
            return [$mainAllocated, $daysExtra, $retarded];
        }

        $daysMain = max(0, ($index % max(1, ($mainAllocated - 1))));
        return [$daysMain, 0, 0];
    }

    private function colorForNiche(string $niche, int $index): string
    {
        $palette = match ($niche) {
            'Lamp' => ['Brass', 'Matte Black', 'Bronze', 'Ivory'],
            'Pouf' => ['Terracotta', 'Cream', 'Charcoal', 'Olive'],
            'Rug' => ['Beige', 'Navy', 'Sage', 'Rust'],
            default => ['White', 'Sand', 'Blue', 'Mustard'],
        };

        return $palette[$index % count($palette)];
    }

    private function noteForScenario(string $niche, ?string $size, float $discountPercent, ?float $productCost): string
    {
        if ($size === null) {
            return "Demo edge case: {$niche} order with missing size for cost matching test.";
        }

        if ($productCost === null) {
            return "Demo edge case: {$niche} {$size} without resolved product cost.";
        }

        if ($discountPercent >= 15) {
            return "Promotional order with higher discount percent applied.";
        }

        return "Standard {$niche} order seeded for calc preview.";
    }

    private function money(float $amount): float
    {
        return round($amount, 2);
    }
}
