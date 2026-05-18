<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\ChartOfAccount;

class ManagementAccountingController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // BASE QUERY (SAFE SELECT ONLY EXISTING COLUMN)
        // =========================
        $query = ChartOfAccount::query()
            ->select([
                'id',
                'parent_id',
                'code',
                'name',
                'type',
                'normal_balance',
                'is_header',
                'is_active',
                'sort_order'
            ]);

        // OPTIONAL: enforce lowercase consistency
        $query->whereIn('type', ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense']);
        // =========================
        // FILTER SAFE BINDING
        // =========================
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', (int) $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // =========================
        // TREE BUILD (NO N+1)
        // =========================
        $accounts = $query
            ->orderBy('code')
            ->get()
            ->groupBy('parent_id');

        // =========================
        // STATS (1 QUERY)
        // =========================
        $stats = ChartOfAccount::query()
            ->selectRaw('
                COUNT(*) as total,
                SUM(is_header = 1) as header,
                SUM(is_active = 1) as active,
                SUM(is_active = 0) as inactive
            ')
            ->first();

        return view('backend.accounting.index', [
            'accounts' => $accounts,
            'stats' => [
                'total' => $stats->total ?? 0,
                'header' => $stats->header ?? 0,
                'active' => $stats->active ?? 0,
                'inactive' => $stats->inactive ?? 0,
            ],
        ]);
    }
}
