<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Accounting\ChartOfAccount;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ManagementAccountingController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:asset,liability,equity,revenue,expense',
            'status' => 'nullable|in:0,1',
            'search' => 'nullable|string|max:100',
        ]);

        // =====================================
        // MAIN QUERY
        // =====================================
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
            ])
            ->whereIn('type', [
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense'
            ]);

        // FILTER TYPE
        if ($request->filled('type')) {

            $query->where(
                'type',
                strtolower($request->type)
            );
        }

        // FILTER STATUS
        if ($request->filled('status')) {

            $query->where(
                'is_active',
                (int) $request->status
            );
        }

        // SEARCH
        if ($request->filled('search')) {

            $search = preg_replace(
                '/\s+/',
                ' ',
                trim($request->search)
            );

            $query->where(function ($q) use ($search) {

                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // =====================================
        // GET DATA
        // =====================================
        $all = $query
            ->orderBy('sort_order')
            ->orderBy('code')
            ->get();

        // TREE GROUPING
        $accounts = $all->groupBy('parent_id');

        // =====================================
        // STATS QUERY
        // =====================================
        $statsQuery = ChartOfAccount::query()
            ->whereIn('type', [
                'asset',
                'liability',
                'equity',
                'revenue',
                'expense'
            ]);

        // FILTER TYPE
        if ($request->filled('type')) {

            $statsQuery->where(
                'type',
                strtolower($request->type)
            );
        }

        // FILTER STATUS
        if ($request->filled('status')) {

            $statsQuery->where(
                'is_active',
                (int) $request->status
            );
        }

        // SEARCH
        if ($request->filled('search')) {

            $search = preg_replace(
                '/\s+/',
                ' ',
                trim($request->search)
            );

            $statsQuery->where(function ($q) use ($search) {

                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // =====================================
        // GET STATS
        // =====================================
        $stats = $statsQuery
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN is_header = 1 THEN 1 ELSE 0 END) as header,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive
            ')
            ->first();

        // =====================================
        // RETURN VIEW
        // =====================================
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

    /* =====================================================
     * STORE
     * ===================================================== */

    public function store(Request $request)
    {
        // =====================================================
        // VALIDATION
        // =====================================================
        $validated = $request->validate([

            'parent_id' => [
                'nullable',
                'integer',
                'exists:chart_of_accounts,id'
            ],

            'type' => [
                'required',
                'in:asset,liability,equity,revenue,expense'
            ],

            'account_mode' => [
                'required',
                'in:header,postable'
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:chart_of_accounts,code'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],

        ]);

        DB::beginTransaction();

        try {

            // =====================================================
            // GET PARENT
            // =====================================================
            $parent = null;

            if ($request->filled('parent_id')) {

                $parent = ChartOfAccount::lockForUpdate()
                    ->findOrFail($request->parent_id);
            }

            // =====================================================
            // VALIDATE PARENT TYPE
            // =====================================================
            if ($parent) {

                if ($parent->type !== $request->type) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'parent_id' => 'Parent account type must match child account type.'
                        ]);
                }
            }

            // =====================================================
            // VALIDATE POSTABLE PARENT
            // =====================================================
            if ($parent && $parent->is_postable == 1) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'parent_id' => 'Postable account cannot have child accounts.'
                    ]);
            }

            // =====================================================
            // AUTO NORMAL BALANCE (IFRS)
            // =====================================================
            $normalBalance = in_array(
                strtolower($request->type),
                ['asset', 'expense']
            )
                ? 'debit'
                : 'credit';

            // =====================================================
            // DETERMINE ACCOUNT MODE
            // =====================================================
            $isHeader = $request->account_mode === 'header' ? 1 : 0;

            $isPostable = $request->account_mode === 'postable' ? 1 : 0;

            // =====================================================
            // DETERMINE LEVEL
            // =====================================================
            $level = 1;

            if ($parent) {

                $level = $parent->level + 1;
            }

            // =====================================================
            // BUILD PARENT PATH
            // =====================================================
            $parentPath = null;

            if ($parent) {

                $parentPath = $parent->parent_path
                    ? $parent->parent_path . '/' . $parent->id
                    : (string) $parent->id;
            }

            // =====================================================
            // SORT ORDER
            // =====================================================
            $lastSort = ChartOfAccount::where(
                'parent_id',
                $request->parent_id
            )->max('sort_order');

            $sortOrder = ($lastSort ?? 0) + 1;

            // =====================================================
            // CREATE ACCOUNT
            // =====================================================
            $account = ChartOfAccount::create([

                'parent_id' => $request->parent_id,

                'parent_path' => $parentPath,

                'code' => trim($request->code),

                'name' => trim($request->name),

                'level' => $level,

                'type' => strtolower($request->type),

                'normal_balance' => $normalBalance,

                'opening_balance' => 0,

                'currency' => 'IDR',

                'is_header' => $isHeader,

                'is_postable' => $isPostable,

                'is_active' => 1,

                'description' => $request->description,

                'sort_order' => $sortOrder,

            ]);

            // =====================================================
            // AUTO UPDATE PARENT
            // =====================================================
            if ($parent) {

                $parent->update([

                    'is_header'   => 1,
                    'is_postable' => 0,

                ]);
            }

            DB::commit();

            // =====================================================
            // SUCCESS
            // =====================================================
            return redirect()
                ->route('management.coa.index')
                ->with('success', 'Chart of account berhasil dibuat.');
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'system' => $e->getMessage()
                ]);
        }
    }

    /* =====================================================
 * UPDATE
 * ===================================================== */
    public function update(Request $request, string $id)
    {
        // =====================================================
        // GET ACCOUNT
        // =====================================================
        $account = ChartOfAccount::findOrFail($id);

        // =====================================================
        // VALIDATION
        // =====================================================
        $validated = $request->validate([

            'parent_id' => [
                'nullable',
                'integer',
                'exists:chart_of_accounts,id'
            ],

            'type' => [
                'required',
                'in:asset,liability,equity,revenue,expense'
            ],

            'account_mode' => [
                'required',
                'in:header,postable'
            ],

            'code' => [
                'required',
                'string',
                'max:50',
                'unique:chart_of_accounts,code,' . $account->id
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

        ]);

        DB::beginTransaction();

        try {

            // =====================================================
            // OLD PARENT
            // =====================================================
            $oldParentId = $account->parent_id;

            // =====================================================
            // GET NEW PARENT
            // =====================================================
            $parent = null;

            if ($request->filled('parent_id')) {

                // =====================================================
                // SELF PARENT VALIDATION
                // =====================================================
                if ((int) $request->parent_id === (int) $account->id) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'parent_id' => 'Account tidak boleh menjadi parent dirinya sendiri.'
                        ]);
                }

                // =====================================================
                // GET PARENT
                // =====================================================
                $parent = ChartOfAccount::lockForUpdate()
                    ->findOrFail($request->parent_id);

                // =====================================================
                // PREVENT CHILD LOOP
                // =====================================================
                if (
                    $parent->parent_path &&
                    str_contains(
                        $parent->parent_path,
                        (string) $account->id
                    )
                ) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'parent_id' => 'Tidak boleh memindahkan account ke child account.'
                        ]);
                }
            }

            // =====================================================
            // VALIDATE PARENT TYPE
            // =====================================================
            if ($parent) {

                if ($parent->type !== $request->type) {

                    return back()
                        ->withInput()
                        ->withErrors([
                            'parent_id' => 'Parent account type harus sama.'
                        ]);
                }
            }

            // =====================================================
            // VALIDATE POSTABLE PARENT
            // =====================================================
            if ($parent && $parent->is_postable == 1) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'parent_id' => 'Postable account tidak boleh memiliki child.'
                    ]);
            }

            // =====================================================
            // AUTO NORMAL BALANCE
            // =====================================================
            $normalBalance = in_array(
                strtolower($request->type),
                ['asset', 'expense']
            )
                ? 'debit'
                : 'credit';

            // =====================================================
            // ACCOUNT MODE
            // =====================================================
            $isHeader = $request->account_mode === 'header' ? 1 : 0;

            $isPostable = $request->account_mode === 'postable' ? 1 : 0;

            // =====================================================
            // LEVEL
            // =====================================================
            $level = 1;

            if ($parent) {

                $level = $parent->level + 1;
            }

            // =====================================================
            // PARENT PATH
            // =====================================================
            $parentPath = null;

            if ($parent) {

                $parentPath = $parent->parent_path
                    ? $parent->parent_path . '/' . $parent->id
                    : (string) $parent->id;
            }

            // =====================================================
            // UPDATE ACCOUNT
            // =====================================================
            $account->update([

                'parent_id'       => $request->parent_id,

                'parent_path'     => $parentPath,

                'code'            => trim($request->code),

                'name'            => trim($request->name),

                'level'           => $level,

                'type'            => strtolower($request->type),

                'normal_balance'  => $normalBalance,

                'is_header'       => $isHeader,

                'is_postable'     => $isPostable,

                'description'     => null,

            ]);

            // =====================================================
            // AUTO UPDATE NEW PARENT
            // =====================================================
            if ($parent) {

                $parent->update([

                    'is_header'   => 1,

                    'is_postable' => 0,

                ]);
            }

            // =====================================================
            // UPDATE OLD PARENT
            // =====================================================
            if ($oldParentId) {

                $childCount = ChartOfAccount::where(
                    'parent_id',
                    $oldParentId
                )->count();

                if ($childCount === 0) {

                    $oldParent = ChartOfAccount::find($oldParentId);

                    if ($oldParent) {

                        $oldParent->update([

                            'is_header'   => 0,

                            'is_postable' => 1,

                        ]);
                    }
                }
            }

            DB::commit();

            // =====================================================
            // SUCCESS
            // =====================================================
            return redirect()
                ->route('management.coa.index')
                ->with('success', 'Chart of account berhasil diupdate.');
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'system' => $e->getMessage()
                ]);
        }
    }

    /* =====================================================
 * TOGGLE STATUS
 * ===================================================== */
    public function toggleStatus(string $id)
    {
        DB::beginTransaction();

        try {

            // =================================================
            // DECRYPT ID (SAFE)
            // =================================================
            $decryptId = Crypt::decryptString($id);

            // force integer safety
            $decryptId = (int) $decryptId;

            // =================================================
            // GET ACCOUNT
            // =================================================
            $account = ChartOfAccount::findOrFail($decryptId);

            // =================================================
            // TOGGLE STATUS
            // =================================================
            $account->is_active = !$account->is_active;
            $account->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $account->is_active
                    ? 'Account berhasil diaktifkan.'
                    : 'Account berhasil dinonaktifkan.',
                'status' => $account->is_active,
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
            ], 500);
        }
    }

    public function detail(string $id)
    {
        $account = ChartOfAccount::with('childrenRecursive')
            ->findOrFail($id);

        return response()->json([
            'id' => $account->id,
            'code' => $account->code,
            'name' => $account->name,
            'type' => $account->type,
            'is_header' => (bool) $account->is_header,
            'normal_balance' => $account->normal_balance,
            'is_active' => (bool) $account->is_active,
            'level' => $account->level,
            'parent_path' => $account->parent_path,
            'tree' => $this->buildTree($account)
        ]);
    }


    // =============================================================
    // RECURSIVE TREE BUILDER (SAFE + CLEAN)
    // =============================================================
    private function buildTree($account)
    {
        return [
            'code' => $account->code,
            'name' => $account->name,

            'children' => $account->childrenRecursive
                ? $account->childrenRecursive->map(function ($child) {
                    return $this->buildTree($child);
                })->values()
                : []
        ];
    }
}
