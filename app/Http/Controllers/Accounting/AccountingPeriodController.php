<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting\AccountingPeriod;
use Illuminate\Support\Facades\DB;
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
            ->with([
                'organization:id,code,name',
                'fiscalYear:id,code,name',
                'closedBy:id,name',
                'lockedBy:id,name',
            ]);
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

    public function changeStatus(Request $request, AccountingPeriod $period)
    {
        try {

            $request->validate([
                'status' => 'required|in:OPEN,CLOSED,LOCKED,ARCHIVED'
            ]);

            $updated = $this->accounting->changePeriodStatus(
                $period->id,
                $request->status
            );

            return response()->json([
                'success' => true,
                'message' => 'Accounting period status updated successfully',
                'data' => [
                    'id' => $updated->id,
                    'status' => $updated->status,
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        } catch (\Exception $e) {

            \Log::error('ACCOUNTING PERIOD STATUS ERROR', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSetting(Request $request, string $id)
    {
        $request->validate([

            'status' => 'required|in:OPEN,CLOSED,LOCKED,ARCHIVED',

            'is_current' => 'required|boolean',

            'allow_transaction' => 'required|boolean',

            'allow_edit' => 'required|boolean',

            'notes' => 'nullable|string',
        ]);

        $period = AccountingPeriod::findOrFail($id);

        DB::beginTransaction();

        try {

            // =========================
            // HANDLE CURRENT PERIOD
            // =========================

            if ($request->is_current) {

                AccountingPeriod::where('organization_id', $period->organization_id)
                    ->where('id', '!=', $period->id)
                    ->update([
                        'is_current' => 0
                    ]);
            }

            // =========================
            // STATUS LOGIC
            // =========================

            $isClosed = false;
            $closedAt = null;
            $closedBy = null;

            if ($request->status === 'CLOSED') {

                $isClosed = true;

                $closedAt = now();

                $closedBy = Auth::id();
            }

            // =========================
            // UPDATE
            // =========================

            $period->update([

                'status' => $request->status,

                'is_current' => (bool) $request->is_current,
                'allow_transaction' => (bool) $request->allow_transaction,
                'allow_edit' => (bool) $request->allow_edit,

                'notes' => $request->notes,

                'is_closed' => $isClosed,
                'closed_at' => $closedAt,
                'closed_by' => $closedBy,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Accounting period updated',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
