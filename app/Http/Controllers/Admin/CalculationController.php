<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyAdsEntry;
use App\Models\Niche;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use App\Services\Calculations\AdsAllocationService;
use App\Services\Calculations\OrderProfitabilityService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CalculationController extends Controller
{
    public function __construct(
        private readonly AdsAllocationService $adsAllocationService,
        private readonly OrderProfitabilityService $orderProfitabilityService,
    ) {
    }

    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);

        [$year, $month] = array_map('intval', explode('-', $filters['month']));
        $monthStart = Carbon::create($year, $month, 1)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $baseMonthOrdersQuery = $this->buildAccessibleOrdersBaseQuery($request->user())
            ->whereBetween('order_date', [$monthStart->toDateString(), $monthEnd->toDateString()]);

        $adsSummary = $this->adsAllocationService->summarize($year, $month, $baseMonthOrdersQuery);

        $ordersQuery = (clone $baseMonthOrdersQuery)
            ->with(['niche', 'store'])
            ->orderBy('order_date')
            ->orderBy('id');

        $this->applyNicheFilter($ordersQuery, $filters['niche']);
        $this->applyStoreFilter($ordersQuery, $filters['store_id']);
        $this->applyDiscountFilter($ordersQuery, $filters['discount_value']);

        $orders = $ordersQuery->get();
        $calculation = $this->orderProfitabilityService->buildRows($orders, $adsSummary['ads_share_per_order']);

        $niches = Niche::orderBy('name')->get();
        $stores = Store::orderBy('name')->get();

        return view('admin.calculations.index', [
            'filters' => $filters,
            'niches' => $niches,
            'stores' => $stores,
            'adsSummary' => $adsSummary,
            'calculationRows' => $calculation['rows'],
            'calculationTotals' => $calculation['totals'],
        ]);
    }

    public function storeAdsEntry(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'entry_date' => ['required', 'date'],
            'coverage' => ['required', 'in:full,partial'],
            'coverage' => ['required', 'in:full,partial'],
            'note' => ['nullable', 'string', 'max:2000'],
            'niche' => ['nullable', 'string'],
            'store_id' => ['nullable', 'string'],
            'discount_value' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        [$year, $month] = array_map('intval', explode('-', $validated['month']));

        MonthlyAdsEntry::create([
            'year' => $year,
            'month' => $month,
            'amount' => (float) $validated['amount'],
            'entry_date' => $validated['entry_date'],
            'is_full_month' => $validated['coverage'] === 'full',
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('admin.calculations.index', [
                'month' => $validated['month'],
                'niche' => $validated['niche'] ?? 'all',
                'store_id' => $validated['store_id'] ?? 'all',
                'discount_value' => $validated['discount_value'] ?? null,
            ])
            ->with('success', 'Monthly ads entry saved.');
    }

    private function resolveFilters(Request $request): array
    {
        $month = $request->query('month', now()->format('Y-m'));
        $niche = $request->query('niche', 'all');
        $storeId = $request->query('store_id', 'all');
        $discountRaw = $request->query('discount_value');
        $discountValue = ($discountRaw === null || $discountRaw === '') ? null : (float) $discountRaw;

        $validated = Validator::make([
            'month' => $month,
            'niche' => $niche,
            'store_id' => $storeId,
            'discount_value' => $discountValue,
        ], [
            'month' => ['required', 'date_format:Y-m'],
            'niche' => ['required', 'string'],
            'store_id' => ['required', 'string'],
            'discount_value' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ])->validate();

        if ($validated['niche'] !== 'all' && $validated['niche'] !== 'unassigned' && !ctype_digit((string) $validated['niche'])) {
            $validated['niche'] = 'all';
        }

        if ($validated['store_id'] !== 'all' && !ctype_digit((string) $validated['store_id'])) {
            $validated['store_id'] = 'all';
        }

        return $validated;
    }

    private function buildAccessibleOrdersBaseQuery(User $user): Builder
    {
        $query = Order::query();

        if ($user->role === 'admin') {
            $allowedStoreIds = $user->stores()->pluck('stores.id')->toArray();
            if (empty($allowedStoreIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('store_id', $allowedStoreIds);
            }
        }

        return $query;
    }

    private function applyNicheFilter(Builder $query, string $nicheFilter): void
    {
        if ($nicheFilter === 'all') {
            return;
        }

        if ($nicheFilter === 'unassigned') {
            $query->whereNull('niche_id');
            return;
        }

        $query->where('niche_id', (int) $nicheFilter);
    }

    private function applyStoreFilter(Builder $query, string $storeId): void
    {
        if ($storeId === 'all') {
            return;
        }

        $query->where('store_id', (int) $storeId);
    }

    private function applyDiscountFilter(Builder $query, ?float $value): void
    {
        if ($value === null) {
            return;
        }

        $query->where('discount_percent', '=', $value);
    }
}
