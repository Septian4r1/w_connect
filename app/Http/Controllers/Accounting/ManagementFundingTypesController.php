<?php

namespace App\Http\Controllers\Accounting;

use Throwable;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Accounting\FundType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ManagementFundingTypesController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     *
     * Optimized:
     * - select only required columns
     * - pagination
     * - scalable query
     * - prevent N+1
     *
     * =========================================================
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $fundTypes = FundType::query()

            ->select([
                'id',
                'code',
                'name',
                'description',
                'is_active',
                'created_at',
            ])

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('code', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%");
                });
            })

            ->orderBy('code')

            ->paginate(15)

            ->withQueryString();

        return view(
            'backend.accounting.funding-types.index',
            compact('fundTypes')
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
}
