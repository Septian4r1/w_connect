<?php

namespace App\Http\Controllers\Accounting;

use Throwable;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Accounting\AccountRole;
use App\Models\Accounting\FundType;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Organization;
use App\Models\FundAccountLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManagementFundingTypesController extends Controller
{
    /**
     * =========================================================
     * INDEX - FUND ACCOUNT MAPPING (FIXED VERSION)
     * =========================================================
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        /*
    |--------------------------------------------------------------------------
    | FUND TYPES
    |--------------------------------------------------------------------------
    */
        $fundTypes = FundType::query()
            ->select([
                'id',
                'code',
                'name',
                'description',
                'is_active'
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('code')
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | FUND ACCOUNT LINKS
    |--------------------------------------------------------------------------
    */
        $fundLinksRaw = FundAccountLink::query()
            ->with([
                'fundType:id,code,name,is_active',
                'coa:id,code,name,type',
                'accountRole:id,code,name,coa_type',
                'organization:id,code,name,type'
            ])
            ->select([
                'id',
                'fund_type_id',
                'coa_id',
                'account_role_id',
                'organization_id',
                'priority',
                'is_default',
                'is_active',
            ])
            ->where('is_active', 1)
            ->orderBy('fund_type_id')
            ->orderBy('organization_id')
            ->orderBy('priority')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | GROUP: fund_type → organization → items
    |--------------------------------------------------------------------------
    */
        $fundLinks = $fundLinksRaw
            ->groupBy('fund_type_id')
            ->map(function ($fundGroup) {

                return $fundGroup
                    ->groupBy('organization_id')
                    ->map(function ($orgGroup) {

                        $first = $orgGroup->first();

                        // SAFE GUARD (ini penting)
                        $org = $first?->organization;

                        return (object) [
                            // RELASI UTAMA
                            'fundType' => $first?->fundType,
                            'organization' => $org,

                            // FLAT CACHE (biar Blade nggak error)
                            'organization_id' => $first?->organization_id,
                            'organization_type' => $org?->type ?? 'unknown',
                            'organization_name' => $org?->name ?? 'Unknown',
                            'organization_code' => $org?->code ?? null,

                            // ITEMS
                            'items' => $orgGroup->values(),
                        ];
                    })
                    ->sortKeys();
            });

        /*
    |--------------------------------------------------------------------------
    | COA LIST
    |--------------------------------------------------------------------------
    */
        $accounts = ChartOfAccount::query()
            ->select([
                'id',
                'code',
                'name',
                'type',
                'is_postable',
                'is_active'
            ])
            ->where('is_active', 1)
            ->where('is_postable', 1)
            ->orderBy('code')
            ->get()
            ->groupBy('type');

        /*
    |--------------------------------------------------------------------------
    | ACCOUNT ROLES
    |--------------------------------------------------------------------------
    */
        $accountRoles = AccountRole::query()
            ->select([
                'id',
                'code',
                'name',
                'coa_type',
                'normal_balance',
            ])
            ->where('is_active', 1)
            ->orderBy('code')
            ->get()
            ->groupBy('coa_type');



        /*
|--------------------------------------------------------------------------
| ORGANIZATIONS
|--------------------------------------------------------------------------
*/
        $organizations = Organization::query()
            ->select([
                'id',
                'type',
                'code',
                'name',
                'parent_id',
                'is_active'
            ])
            ->where('is_active', 1)
            ->orderBy('type')
            ->orderBy('code')
            ->get();
        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */
        return view(
            'backend.accounting.funding-types.index',
            compact(
                'fundTypes',
                'fundLinks',
                'accounts',
                'accountRoles',
                'organizations' // ✅ TAMBAHKAN INI
            )
        );
    }

    /**
     * =========================================================
     * STORE
     * =========================================================
     */
    public function store(Request $request): RedirectResponse
    {
        try {

            $validated = $request->validate([

                'code' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('fund_types', 'code'),
                ],

                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],

                'is_active' => [
                    'required',
                    'boolean',
                ],

            ]);

            DB::beginTransaction();

            FundType::create([

                'code' => strip_tags(trim($validated['code'])),

                'name' => strip_tags(trim($validated['name'])),

                'description' => isset($validated['description'])
                    ? strip_tags(trim($validated['description']))
                    : null,

                'is_active' => (bool) $validated['is_active'],

            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Funding type berhasil ditambahkan.'
                );
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('ERROR STORE FUND TYPE', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' => 'Terjadi kesalahan saat menyimpan funding type.'
                ]);
        }
    }

    /**
     * =========================================================
     * UPDATE
     * =========================================================
     *
     * RULE:
     * Jika status diubah:
     * - pastikan tidak ada relasi aktif
     * - jika masih dipakai transaksi:
     *   status tidak boleh diubah
     *
     * =========================================================
     */
    public function update(
        Request $request,
        string $id
    ): RedirectResponse {

        try {

            $fundType = FundType::query()

                ->select([
                    'id',
                    'code',
                    'name',
                    'description',
                    'is_active',
                ])

                ->findOrFail($id);

            $validated = $request->validate([

                'code' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('fund_types', 'code')
                        ->ignore($fundType->id),
                ],

                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'description' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],

                'is_active' => [
                    'required',
                    'boolean',
                ],

            ]);

            DB::beginTransaction();

            // =====================================================
            // CHECK STATUS CHANGE
            // =====================================================
            $oldStatus = (bool) $fundType->is_active;

            $newStatus = (bool) $validated['is_active'];

            /*
            |--------------------------------------------------------------------------
            | VALIDASI RELASI
            |--------------------------------------------------------------------------
            | Jika status berubah:
            | cek apakah fund type masih dipakai
            |--------------------------------------------------------------------------
            */

            if ($oldStatus !== $newStatus) {

                /*
                |--------------------------------------------------------------------------
                | EXAMPLE RELATION CHECK
                |--------------------------------------------------------------------------
                | Ganti sesuai relasi asli:
                |
                | fundingTransactions()
                | journals()
                | invoices()
                | payments()
                |--------------------------------------------------------------------------
                */

                $hasRelations = false;

                // =================================================
                // CONTOH CEK RELASI
                // =================================================

                if (method_exists($fundType, 'fundingTransactions')) {

                    $hasRelations = $fundType
                        ->fundingTransactions()
                        ->exists();
                }

                if (!$hasRelations && method_exists($fundType, 'journals')) {

                    $hasRelations = $fundType
                        ->journals()
                        ->exists();
                }

                if ($hasRelations) {

                    DB::rollBack();

                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors([
                            'error' =>
                            'Status funding type tidak dapat diubah karena masih digunakan pada data transaksi atau laporan keuangan.'
                        ]);
                }
            }

            // =====================================================
            // UPDATE
            // =====================================================
            $fundType->update([

                'code' => strip_tags(trim($validated['code'])),

                'name' => strip_tags(trim($validated['name'])),

                'description' => isset($validated['description'])
                    ? strip_tags(trim($validated['description']))
                    : null,

                'is_active' => $newStatus,

            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Funding type berhasil diupdate.'
                );
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('ERROR UPDATE FUND TYPE', [

                'fund_type_id' => $id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'error' =>
                    'Terjadi kesalahan saat mengupdate funding type.'
                ]);
        }
    }

    /**
     * =========================================================
     * DELETE
     * =========================================================
     *
     * RULE:
     * Tidak boleh hapus jika masih dipakai
     *
     * =========================================================
     */
    public function destroy(string $id): RedirectResponse
    {
        try {

            $fundType = FundType::query()

                ->select([
                    'id',
                    'code',
                    'name',
                ])

                ->findOrFail($id);

            DB::beginTransaction();

            // =====================================================
            // RELATION CHECK
            // =====================================================
            $hasRelations = false;

            /*
            |--------------------------------------------------------------------------
            | GANTI SESUAI RELASI ASLI
            |--------------------------------------------------------------------------
            */

            if (method_exists($fundType, 'fundingTransactions')) {

                $hasRelations = $fundType
                    ->fundingTransactions()
                    ->exists();
            }

            if (!$hasRelations && method_exists($fundType, 'journals')) {

                $hasRelations = $fundType
                    ->journals()
                    ->exists();
            }

            // =====================================================
            // BLOCK DELETE
            // =====================================================
            if ($hasRelations) {

                DB::rollBack();

                return redirect()
                    ->back()
                    ->withErrors([
                        'error' =>
                        'Funding type tidak dapat dihapus karena masih digunakan pada transaksi atau laporan keuangan.'
                    ]);
            }

            // =====================================================
            // DELETE
            // =====================================================
            $fundType->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Funding type berhasil dihapus.'
                );
        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('ERROR DELETE FUND TYPE', [

                'fund_type_id' => $id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            return redirect()
                ->back()
                ->withErrors([
                    'error' =>
                    'Terjadi kesalahan saat menghapus funding type.'
                ]);
        }
    }


    public function getCoas(string $fundId)
    {
        $links = FundAccountLink::query()

            ->with([
                'coa:id,code,name,type',
                'accountRole:id,code,name,coa_type'
            ])

            ->where('fund_type_id', $fundId)

            ->where('is_active', 1)

            ->get();

        return response()->json([

            /*
        |--------------------------------------------------------------------------
        | CASH
        |--------------------------------------------------------------------------
        */
            'cash' => $links

                ->filter(function ($item) {

                    return strtolower(
                        $item->accountRole?->code ?? ''
                    ) === 'cash';
                })

                ->values()

                ->map(function ($item) {

                    return [
                        'id' => $item->coa?->id,
                        'code' => $item->coa?->code,
                        'name' => $item->coa?->name,
                    ];
                }),

            /*
        |--------------------------------------------------------------------------
        | BANK
        |--------------------------------------------------------------------------
        */
            'bank' => $links

                ->filter(function ($item) {

                    return strtolower(
                        $item->accountRole?->code ?? ''
                    ) === 'bank';
                })

                ->values()

                ->map(function ($item) {

                    return [
                        'id' => $item->coa?->id,
                        'code' => $item->coa?->code,
                        'name' => $item->coa?->name,
                    ];
                }),

            /*
        |--------------------------------------------------------------------------
        | EXPENSE
        |--------------------------------------------------------------------------
        */
            'expense' => $links

                ->filter(function ($item) {

                    return strtolower(
                        $item->accountRole?->coa_type ?? ''
                    ) === 'expense';
                })

                ->values()

                ->map(function ($item) {

                    return [
                        'id' => $item->coa?->id,
                        'code' => $item->coa?->code,
                        'name' => $item->coa?->name,
                    ];
                }),

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FUND ACCOUNT LINKS
    |--------------------------------------------------------------------------
    */


    public function editAccountMapping(string $encryptedId)
    {
        try {
            /*
        |---------------------------------------------------------
        | 1. DECRYPT ID
        |---------------------------------------------------------
        */
            $id = decrypt($encryptedId);

            /*
        |---------------------------------------------------------
        | 2. GET CURRENT ROW
        |---------------------------------------------------------
        */
            $current = FundAccountLink::query()
                ->with([
                    'fundType:id,code,name,is_active',
                    'organization:id,type,code,name,is_active',
                ])
                ->findOrFail($id);

            $fundTypeId = $current->fund_type_id;
            $organizationId = $current->organization_id;

            /*
        |---------------------------------------------------------
        | 3. GET ALL MAPPINGS (NO N+1)
        |---------------------------------------------------------
        */
            $mappings = FundAccountLink::query()
                ->with([
                    'fundType:id,code,name',
                    'organization:id,type,code,name',
                    'coa:id,code,name,type',
                    'accountRole:id,code,name,coa_type',
                ])
                ->where('fund_type_id', $fundTypeId)
                ->where('organization_id', $organizationId)
                ->orderBy('priority')
                ->get();

            /*
        |---------------------------------------------------------
        | 4. FAST LOOKUP (IMPORTANT FOR CHECKBOX)
        |---------------------------------------------------------
        */
            $mappedCoaIds = $mappings
                ->pluck('coa_id')
                ->unique()
                ->flip(); // O(1) lookup

            /*
        |---------------------------------------------------------
        | 5. DEFAULT PER TYPE (FIXED & CLEAN)
        |---------------------------------------------------------
        */
            $defaultByType = $mappings
                ->where('is_default', 1)
                ->mapWithKeys(function ($item) {
                    $type = strtolower($item->coa?->type ?? $item->accountRole?->coa_type);

                    return [$type => $item->coa_id];
                });

            /*
        |---------------------------------------------------------
        | 6. GROUP STRUCTURE (OPTIONAL UI USE)
        |---------------------------------------------------------
        */
            $grouped = $mappings
                ->groupBy('fund_type_id')
                ->map(function ($fundGroup) {

                    return $fundGroup
                        ->groupBy('organization_id')
                        ->map(function ($orgGroup) {

                            $first = $orgGroup->first();

                            return (object) [
                                'fundType' => $first->fundType,
                                'organization' => $first->organization,

                                'fund_type_id' => $first->fund_type_id,
                                'organization_id' => $first->organization_id,

                                'organization_name' => $first->organization?->name,
                                'organization_code' => $first->organization?->code,

                                'items' => $orgGroup->values(),
                            ];
                        });
                });

            /*
        |---------------------------------------------------------
        | 7. SUPPORT DATA (NO N+1)
        |---------------------------------------------------------
        */
            $fundTypes = FundType::query()
                ->select('id', 'code', 'name')
                ->orderBy('code')
                ->get();

            $organizations = Organization::query()
                ->select('id', 'type', 'code', 'name')
                ->orderBy('type')
                ->orderBy('code')
                ->get();

            $accounts = ChartOfAccount::query()
                ->select('id', 'code', 'name', 'type', 'is_postable')
                ->where('is_active', 1)
                ->where('is_postable', 1)
                ->orderBy('code')
                ->get()
                ->groupBy('type');

            /*
        |---------------------------------------------------------
        | 8. RETURN VIEW
        |---------------------------------------------------------
        */
            return view('backend.accounting.funding-types.edit-mapping', [
                'current' => $current,
                'grouped' => $grouped,
                'fundTypes' => $fundTypes,
                'organizations' => $organizations,
                'accounts' => $accounts,

                // IMPORTANT
                'mappedCoaIds' => $mappedCoaIds,
                'defaultByType' => $defaultByType,
            ]);
        } catch (\Throwable $e) {

            report($e);

            return back()->withErrors([
                'error' => 'Failed to load edit mapping data: ' . $e->getMessage()
            ]);
        }
    }


    public function updateAccountMapping(
        Request $request,
        string $encryptedId
    ): RedirectResponse {

        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | DECRYPT ID
        |--------------------------------------------------------------------------
        */
            $id = decrypt($encryptedId);

            /*
        |--------------------------------------------------------------------------
        | CURRENT DATA
        |--------------------------------------------------------------------------
        */
            $current = FundAccountLink::query()->findOrFail($id);

            /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
            $validated = $request->validate([

                'fund_type_id' => [
                    'required',
                    'integer',
                    'exists:fund_types,id',
                ],

                'organization_id' => [
                    'required',
                    'integer',
                    'exists:organizations,id',
                ],

                'mapping' => [
                    'nullable',
                    'array',
                ],

                'mapping.asset' => [
                    'nullable',
                    'array',
                ],

                'mapping.liability' => [
                    'nullable',
                    'array',
                ],

                'mapping.revenue' => [
                    'nullable',
                    'array',
                ],

                'mapping.expense' => [
                    'nullable',
                    'array',
                ],

                'mapping.*.*' => [
                    'nullable',
                    'integer',
                    'exists:chart_of_accounts,id',
                ],

                'default_asset_id' => [
                    'nullable',
                    'integer',
                    'exists:chart_of_accounts,id',
                ],

                'default_liability_id' => [
                    'nullable',
                    'integer',
                    'exists:chart_of_accounts,id',
                ],

                'default_revenue_id' => [
                    'nullable',
                    'integer',
                    'exists:chart_of_accounts,id',
                ],

                'default_expense_id' => [
                    'nullable',
                    'integer',
                    'exists:chart_of_accounts,id',
                ],

            ]);

            /*
        |--------------------------------------------------------------------------
        | DELETE OLD MAPPING
        |--------------------------------------------------------------------------
        */
            FundAccountLink::query()

                ->where('fund_type_id', $current->fund_type_id)

                ->where('organization_id', $current->organization_id)

                ->delete();

            /*
        |--------------------------------------------------------------------------
        | ROLE MAP
        |--------------------------------------------------------------------------
        */
            $roleMap = [

                'asset' => 'CASH',

                'liability' => 'FUND_BALANCE',

                'revenue' => 'REVENUE',

                'expense' => 'EXPENSE',

            ];

            /*
        |--------------------------------------------------------------------------
        | GET ALL ROLES
        |--------------------------------------------------------------------------
        */
            $roles = AccountRole::query()

                ->whereIn('code', array_values($roleMap))

                ->get()

                ->keyBy('code');

            /*
        |--------------------------------------------------------------------------
        | DEBUG RESULT
        |--------------------------------------------------------------------------
        */
            $debug = [];

            /*
        |--------------------------------------------------------------------------
        | LOOP ROLE TYPES
        |--------------------------------------------------------------------------
        */
            foreach ($roleMap as $type => $roleCode) {

                /*
            |--------------------------------------------------------------------------
            | GET ROLE
            |--------------------------------------------------------------------------
            */
                $role = $roles->get($roleCode);

                /*
            |--------------------------------------------------------------------------
            | SKIP IF ROLE NOT FOUND
            |--------------------------------------------------------------------------
            */
                if (!$role) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | GET COA IDS
            |--------------------------------------------------------------------------
            */
                $coaIds = $validated['mapping'][$type] ?? [];

                /*
            |--------------------------------------------------------------------------
            | FORCE ARRAY
            |--------------------------------------------------------------------------
            */
                if (!is_array($coaIds)) {
                    $coaIds = [$coaIds];
                }

                /*
            |--------------------------------------------------------------------------
            | CLEAN EMPTY VALUE
            |--------------------------------------------------------------------------
            */
                $coaIds = array_values(array_filter($coaIds));

                /*
            |--------------------------------------------------------------------------
            | REMOVE DUPLICATE
            |--------------------------------------------------------------------------
            */
                $coaIds = array_unique($coaIds);

                /*
            |--------------------------------------------------------------------------
            | DEFAULT ID
            |--------------------------------------------------------------------------
            */
                $defaultId = $request->input("default_{$type}_id");

                /*
            |--------------------------------------------------------------------------
            | AUTO DEFAULT
            |--------------------------------------------------------------------------
            */
                if (empty($defaultId) && count($coaIds) > 0) {
                    $defaultId = $coaIds[0];
                }

                /*
            |--------------------------------------------------------------------------
            | VALIDATE DEFAULT EXISTS IN MAPPING
            |--------------------------------------------------------------------------
            */
                if (
                    !empty($defaultId) &&
                    !in_array($defaultId, $coaIds)
                ) {
                    $defaultId = $coaIds[0] ?? null;
                }

                /*
            |--------------------------------------------------------------------------
            | LOOP COA IDS
            |--------------------------------------------------------------------------
            */
                foreach ($coaIds as $index => $coaId) {

                    /*
                |--------------------------------------------------------------------------
                | INSERT DATA
                |--------------------------------------------------------------------------
                */
                    $created = FundAccountLink::create([

                        'fund_type_id' =>
                        $validated['fund_type_id'],

                        'organization_id' =>
                        $validated['organization_id'],

                        'account_role_id' =>
                        $role->id,

                        'coa_id' =>
                        $coaId,

                        'priority' =>
                        $index + 1,

                        'is_default' =>
                        (int) ((string) $defaultId === (string) $coaId),

                        'is_active' => 1,

                    ]);

                    /*
                |--------------------------------------------------------------------------
                | DEBUG
                |--------------------------------------------------------------------------
                */
                    $debug[] = [

                        'id' => $created->id,

                        'type' => $type,

                        'role_code' => $roleCode,

                        'role_id' => $role->id,

                        'coa_id' => $coaId,

                        'default_id' => $defaultId,

                        'is_default' =>
                        (int) ((string) $defaultId === (string) $coaId),

                        'priority' =>
                        $index + 1,

                    ];
                }
            }

            /*
        |--------------------------------------------------------------------------
        | VALIDATION RESULT
        |--------------------------------------------------------------------------
        */
            if (empty($debug)) {

                DB::rollBack();

                return redirect()

                    ->back()

                    ->withInput()

                    ->withErrors([
                        'mapping' =>
                        'Minimal satu mapping account harus dipilih.',
                    ]);
            }

            /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */
            DB::commit();

            /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */
            return redirect()

                ->route('management.funding-types.index')

                ->with(
                    'success',
                    'Fund account mapping berhasil diperbarui.'
                );
        } catch (ValidationException $e) {

            DB::rollBack();

            throw $e;
        } catch (Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()

                ->back()

                ->withInput()

                ->withErrors([

                    'error' =>
                    'Terjadi kesalahan saat update mapping: ' .
                        $e->getMessage(),

                ]);
        }
    }





    public function destroyAccount(string $fundTypeId, string $organizationId)
    {
        try {

            $deleted = FundAccountLink::where('fund_type_id', $fundTypeId)
                ->where('organization_id', $organizationId)
                ->delete();

            return back()->with(
                $deleted ? 'success' : 'error',
                $deleted ? 'Fund mapping berhasil dihapus.' : 'Data tidak ditemukan.'
            );
        } catch (Throwable $e) {

            Log::error('DELETE FUND ERROR', [
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan.');
        }
    }
}
