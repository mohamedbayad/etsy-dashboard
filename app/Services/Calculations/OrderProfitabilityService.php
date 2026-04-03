<?php

namespace App\Services\Calculations;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderProfitabilityService
{
    private array $sheetRowsCache = [];

    public function __construct(private readonly NicheSheetService $nicheSheetService)
    {
    }

    public function buildRows(Collection $orders, float $adsSharePerOrder): array
    {
        $rows = [];
        $totals = [
            'orders_count' => 0,
            'revenue' => 0.0,
            'effective_revenue' => 0.0,
            'etsy_fee' => 0.0,
            'product_cost' => 0.0,
            'shipping_cost' => 0.0,
            'ads' => 0.0,
            'profit' => 0.0,
            'profit_computed_count' => 0,
            'missing_product_cost_count' => 0,
        ];

        foreach ($orders as $order) {
            /** @var Order $order */
            $costResolution = $this->resolveProductCost($order);

            $orderPrice = (float) ($order->price ?? 0);
            $discountPercent = $this->normalizeDiscountPercent($order->discount_percent);
            $effectiveOrderPrice = round($orderPrice * (1 - ($discountPercent / 100)), 2);
            $etsyFee = round($effectiveOrderPrice * 0.11, 2);
            $shippingCost = (float) ($order->shipping_cost ?? 0);
            $productCost = $costResolution['product_cost'];
            $profit = $productCost === null
                ? null
                : round($effectiveOrderPrice - $etsyFee - $productCost - $shippingCost - $adsSharePerOrder, 2);

            $rows[] = [
                'order' => $order,
                'order_price' => round($orderPrice, 2),
                'discount_percent' => $discountPercent,
                'effective_order_price' => $effectiveOrderPrice,
                'etsy_fee' => $etsyFee,
                'product_cost' => $productCost,
                'shipping_cost' => round($shippingCost, 2),
                'ads_share' => round($adsSharePerOrder, 2),
                'final_profit' => $profit,
                'match_status' => $costResolution['status'],
                'warning' => $costResolution['warning'],
                'cost_source' => $costResolution['source'],
            ];

            $totals['orders_count']++;
            $totals['revenue'] += $orderPrice;
            $totals['effective_revenue'] += $effectiveOrderPrice;
            $totals['etsy_fee'] += $etsyFee;
            $totals['shipping_cost'] += $shippingCost;
            $totals['ads'] += $adsSharePerOrder;

            if ($productCost === null) {
                $totals['missing_product_cost_count']++;
            } else {
                $totals['product_cost'] += $productCost;
            }

            if ($profit !== null) {
                $totals['profit'] += $profit;
                $totals['profit_computed_count']++;
            }
        }

        foreach (['revenue', 'effective_revenue', 'etsy_fee', 'product_cost', 'shipping_cost', 'ads', 'profit'] as $key) {
            $totals[$key] = round($totals[$key], 2);
        }

        return [
            'rows' => $rows,
            'totals' => $totals,
        ];
    }

    private function resolveProductCost(Order $order): array
    {
        if ($order->product_cost !== null) {
            return [
                'product_cost' => round((float) $order->product_cost, 2),
                'status' => 'resolved',
                'source' => 'order',
                'warning' => null,
            ];
        }

        if ($order->niche_id === null) {
            return $this->missingCost('missing_niche', 'Order niche is missing.');
        }

        if (!$order->relationLoaded('niche')) {
            $order->load('niche');
        }

        if ($order->niche === null) {
            return $this->missingCost('missing_niche', 'Order niche record was not found.');
        }

        if ($order->size === null || trim($order->size) === '') {
            return $this->missingCost('missing_size', 'Order size is missing.');
        }

        $lookup = $this->sheetRowsCache[$order->niche_id] ?? null;
        if ($lookup === null) {
            $sheetRowsResult = $this->nicheSheetService->fetchPricingRows($order->niche);
            if (!$sheetRowsResult['ok']) {
                $warning = $sheetRowsResult['message'] ?? 'Unable to read niche sheet.';
                $status = match ($sheetRowsResult['error_type']) {
                    'missing_sheet' => 'missing_sheet',
                    'invalid_sheet_id' => 'invalid_sheet_id',
                    'private_sheet' => 'private_sheet',
                    default => 'sheet_unreachable',
                };
                $this->sheetRowsCache[$order->niche_id] = ['status' => $status, 'warning' => $warning, 'rows' => []];
            } else {
                $this->sheetRowsCache[$order->niche_id] = [
                    'status' => 'ok',
                    'warning' => null,
                    'rows' => $sheetRowsResult['rows'],
                ];
            }

            $lookup = $this->sheetRowsCache[$order->niche_id];
        }

        if (($lookup['status'] ?? null) !== 'ok') {
            return $this->missingCost($lookup['status'] ?? 'sheet_unreachable', $lookup['warning'] ?? 'Niche sheet unavailable.');
        }

        $normalizedTargetSize = $this->nicheSheetService->normalizeSize((string) $order->size);
        $matchedRow = collect($lookup['rows'])->first(function (array $row) use ($normalizedTargetSize) {
            return $this->nicheSheetService->normalizeSize((string) ($row['size'] ?? '')) === $normalizedTargetSize;
        });

        if ($matchedRow === null) {
            return $this->missingCost('size_not_found', 'Size was not found in the niche sheet.');
        }

        $rawPrice = trim((string) ($matchedRow['price'] ?? ''));
        if ($rawPrice === '') {
            return $this->missingCost('price_missing', 'Matched sheet row has no price.');
        }

        $priceInDh = $this->nicheSheetService->parsePriceToFloat($rawPrice);
        if ($priceInDh === null) {
            return $this->missingCost('price_missing', 'Matched sheet price is not numeric.');
        }

        $priceInUsd = $this->nicheSheetService->convertDhPriceToUsd($priceInDh);
        $order->forceFill(['product_cost' => $priceInUsd])->save();

        return [
            'product_cost' => $priceInUsd,
            'status' => 'resolved',
            'source' => 'niche_sheet',
            'warning' => null,
        ];
    }

    private function missingCost(string $status, string $warning): array
    {
        return [
            'product_cost' => null,
            'status' => $status,
            'source' => 'missing',
            'warning' => $warning,
        ];
    }

    private function normalizeDiscountPercent(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_string($value)) {
            $value = str_replace('%', '', $value);
        }

        if (!is_numeric($value)) {
            return 0.0;
        }

        return round(max(0, min(100, (float) $value)), 2);
    }
}
