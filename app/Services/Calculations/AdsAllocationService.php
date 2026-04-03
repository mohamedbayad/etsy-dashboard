<?php

namespace App\Services\Calculations;

use App\Models\MonthlyAdsEntry;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class AdsAllocationService
{
    public function summarize(int $year, int $month, Builder $ordersScope): array
    {
        $entriesQuery = MonthlyAdsEntry::query()
            ->forMonth($year, $month)
            ->orderByDesc('entry_date')
            ->orderByDesc('id');

        $entries = $entriesQuery->get();
        $totalAds = (float) $entries->sum('amount');
        $hasFullMonthEntry = $entries->contains(fn (MonthlyAdsEntry $entry) => $entry->is_full_month);

        $ordersCount = (clone $ordersScope)->count();
        $adsSharePerOrder = $ordersCount > 0 ? round($totalAds / $ordersCount, 2) : 0.0;

        return [
            'entries' => $entries,
            'total_ads' => round($totalAds, 2),
            'orders_count' => $ordersCount,
            'ads_share_per_order' => $adsSharePerOrder,
            'month_state' => $hasFullMonthEntry ? 'full' : 'partial',
        ];
    }
}

