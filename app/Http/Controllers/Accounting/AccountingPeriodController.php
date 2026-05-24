<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\AccountingPeriod;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use App\Services\Accounting\AccountingService;
use App\Services\Accounting\FiscalYearService;

class AccountingPeriodController extends Controller
{
    /*
    |---------------------------------------------------
    | LIST PERIOD (ERP VIEW)
    |---------------------------------------------------
    | Menampilkan semua periode accounting
    | dengan urutan terbaru
    */


    protected AccountingService $accounting;
    protected FiscalYearService $fiscalService;

    public function __construct(
        AccountingService $accounting,
        FiscalYearService $fiscalService
    ) {
        $this->accounting = $accounting;
        $this->fiscalService = $fiscalService;
    }


    public function index(Request $request)
    {
        // =========================
        // BASE QUERY (NO N+1 SAFE)
        // =========================
        $query = AccountingPeriod::query()
            ->with(['organization:id,code,name']);
        // ✅ hanya field penting (lebih cepat)

        // =========================
        // SEARCH FILTER
        // =========================
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        });

        // =========================
        // STATUS FILTER
        // =========================
        $query->when($request->status, function ($q, $status) {
            $q->where('status', $status);
        });

        // =========================
        // PAGINATION (KEEP FILTER)
        // =========================
        $periods = $query
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        // =========================
        // STATS (GLOBAL, FAST QUERY)
        // =========================
        $stats = AccountingPeriod::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'OPEN' THEN 1 ELSE 0 END) as open_count,
            SUM(CASE WHEN status = 'CLOSED' THEN 1 ELSE 0 END) as closed_count,
            SUM(CASE WHEN status = 'LOCKED' THEN 1 ELSE 0 END) as locked_count
        ")
            ->first();

        $stats = [
            'total' => $stats->total ?? 0,
            'open' => $stats->open_count ?? 0,
            'closed' => $stats->closed_count ?? 0,
            'locked' => $stats->locked_count ?? 0,
        ];

        // =========================
        // MASTER DATA (FORM ONLY)
        // =========================
        $organizations = Organization::select('id', 'code', 'name')->get();

        $currentYear = now()->year;
        $currentMonth = now()->month;

        $years = range($currentYear - 2, $currentYear + 8);

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        // =========================
        // RETURN VIEW
        // =========================
        return view('backend.accounting.accounting_periode.index', compact(
            'periods',
            'stats',
            'organizations',
            'years',
            'months',
            'currentYear',
            'currentMonth'
        ));
    }

    /*
    |---------------------------------------------------
    | STORE FISCAL (ERP SAFE CREATION)
    |---------------------------------------------------
    | Membuat fiscal accounting baru per tahun
    | dengan standar IFRS period control
    */

    public function FiscalStore(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'year' => 'required|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        try {

            $fiscal = $this->fiscalService->create($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Fiscal year created successfully',
                    'data' => $fiscal
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Fiscal year created successfully');
        } catch (\Exception $e) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |---------------------------------------------------
    | STORE PERIOD (ERP SAFE CREATION)
    |---------------------------------------------------
    | Membuat periode accounting baru per bulan
    | dengan standar IFRS period control
    */
    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);

        try {

            $period = $this->accounting->createPeriod($data);

            // ✅ selalu return JSON jika fetch / ajax
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Accounting period created successfully',
                    'data' => $period
                ]);
            }

            return redirect()
                ->route('accounting.periods.index')
                ->with('success', 'Accounting period created successfully');
        } catch (\Exception $e) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |---------------------------------------------------
    | SET CURRENT PERIOD (ERP CONTROL RULE)
    |---------------------------------------------------
    | Hanya 1 period aktif per organisasi
    */
    public function setCurrent(int $id)
    {
        $period = AccountingPeriod::findOrFail($id);

        // 🚨 SAFETY CHECK
        if ($period->isClosed()) {
            return back()->with('error', 'Cannot set closed period as current');
        }

        // 🚨 BUG FIX: avoid null organization mass update issue
        $query = AccountingPeriod::query();

        if ($period->organization_id) {
            $query->where('organization_id', $period->organization_id);
        } else {
            $query->whereNull('organization_id');
        }

        $query->update(['is_current' => false]);

        $period->update([
            'is_current' => true,
        ]);

        return back()->with('success', 'Current period updated successfully');
    }

    /*
    |---------------------------------------------------
    | CLOSE PERIOD (IFRS STYLE CLOSING)
    |---------------------------------------------------
    | Lock semua transaksi agar tidak bisa diubah
    */
    public function close(int $id)
    {
        $period = AccountingPeriod::findOrFail($id);

        // 🚨 RULE: hanya period OPEN yang bisa ditutup
        if (!$period->isOpen()) {
            return back()->with('error', 'Only OPEN period can be closed');
        }

        $period->update([
            'status' => 'CLOSED',
            'is_closed' => true,
            'is_current' => false,

            'closed_at' => now(),
            'closed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Period closed successfully');
    }

    /*
    |---------------------------------------------------
    | LOCK PERIOD (ADVANCED ERP CONTROL)
    |---------------------------------------------------
    | Setelah audit → tidak bisa diubah sama sekali
    */
    public function lock(int $id)
    {
        $period = AccountingPeriod::findOrFail($id);

        if (!$period->isClosed()) {
            return back()->with('error', 'Period must be CLOSED before LOCKED');
        }

        $period->update([
            'status' => 'LOCKED',
            'locked_at' => now(),
            'locked_by' => Auth::id(),
        ]);

        return back()->with('success', 'Period locked successfully');
    }
}
