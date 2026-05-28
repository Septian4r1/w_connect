<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Accounting\FundType;
use App\Models\Accounting\FundTypeAmount;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class ManagementFundingAmountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        /*
    |--------------------------------------------------------------------------
    | SANITIZE INPUT
    |--------------------------------------------------------------------------
    */

        $search = trim(
            strip_tags(
                (string) $request->get('search')
            )
        );

        // ⚠️ FIX: jangan pakai integer() karena "0" jadi false logic bug
        $organizationId = $request->get('organization_id');
        $status = $request->get('status');

        /*
    |--------------------------------------------------------------------------
    | BASE QUERY
    |--------------------------------------------------------------------------
    */

        $query = FundTypeAmount::query()
            ->with([
                'organization:id,code,name,type',
                'fundType:id,code,name,description,is_active',
            ])
            ->select([
                'id',
                'organization_id',
                'reference_no',
                'fund_type_id',
                'amount',
                'description',
                'is_active',
                'created_at',
            ]);

        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        $query->when(
            filled($search),
            function ($q) use ($search) {

                $q->where(function ($sub) use ($search) {

                    // FUND TYPE
                    $sub->whereHas('fundType', function ($fundQuery) use ($search) {
                        $fundQuery->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });

                    // ORGANIZATION
                    $sub->orWhereHas('organization', function ($orgQuery) use ($search) {
                        $orgQuery->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });

                    // DESCRIPTION
                    $sub->orWhere('description', 'like', "%{$search}%");
                });
            }
        );

        /*
    |--------------------------------------------------------------------------
    | FILTER : ORGANIZATION (FIXED)
    |--------------------------------------------------------------------------
    */

        $query->when(
            !empty($organizationId),
            fn($q) => $q->where('organization_id', $organizationId)
        );

        /*
    |--------------------------------------------------------------------------
    | FILTER : STATUS (FIXED SAFE CAST)
    |--------------------------------------------------------------------------
    */

        $query->when(
            $status !== null && $status !== '',
            fn($q) => $q->where('is_active', (int) $status)
        );

        /*
    |--------------------------------------------------------------------------
    | SORTING
    |--------------------------------------------------------------------------
    */

        $query->orderByDesc('is_active')
            ->orderBy('amount')
            ->orderByDesc('id');

        /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

        $fundTypeAmounts = $query
            ->paginate(10)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | ORGANIZATION FILTER
    |--------------------------------------------------------------------------
    */

        $organizations = Organization::query()
            ->select(['id', 'code', 'name', 'type'])
            ->active()
            ->orderBy('type')
            ->orderBy('code')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | FUNDING TYPES
    |--------------------------------------------------------------------------
    */

        $fundingTypes = FundType::query()
            ->select(['id', 'code', 'name', 'description', 'is_active'])
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

        $summary = [
            'total_amount' => FundTypeAmount::query()
                ->active()
                ->sum('amount'),

            'active_count' => FundTypeAmount::query()
                ->where('is_active', true)
                ->count(),

            'inactive_count' => FundTypeAmount::query()
                ->where('is_active', false)
                ->count(),

            'organization_count' => FundTypeAmount::query()
                ->distinct('organization_id')
                ->count('organization_id'),
        ];

        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

        return view(
            'backend.accounting.funding-amount.index',
            compact(
                'fundTypeAmounts',
                'organizations',
                'fundingTypes',
                'summary'
            )
        );
    }

    public function store(
        Request $request
    ): JsonResponse {

        /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([

            'organization_id' => [
                'required',
                'integer',
                'exists:organizations,id',
            ],

            'funding_type_id' => [
                'required',
                'integer',
                'exists:fund_types,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'funding_date' => [
                'required',
                'date',
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

        try {

            /*
        |--------------------------------------------------------------------------
        | ORGANIZATION
        |--------------------------------------------------------------------------
        */

            $organization = Organization::query()

                ->select([
                    'id',
                    'code',
                    'name',
                    'type',
                ])

                ->lockForUpdate()

                ->findOrFail(
                    $validated['organization_id']
                );

            /*
        |--------------------------------------------------------------------------
        | SANITIZE ORGANIZATION CODE
        |--------------------------------------------------------------------------
        |
        | Example:
        | RW 001 => RW001
        |
        */

            $organizationCode = strtoupper(

                preg_replace(
                    '/[^A-Za-z0-9]/',
                    '',
                    $organization->code
                )
            );

            /*
        |--------------------------------------------------------------------------
        | DATE PREFIX
        |--------------------------------------------------------------------------
        |
        | Example:
        | 202605
        |
        */

            $datePrefix = now()->format('Ym');

            /*
        |--------------------------------------------------------------------------
        | REFERENCE PREFIX
        |--------------------------------------------------------------------------
        |
        | Example:
        | FUND-RW001-202605
        |
        */

            $referencePrefix = sprintf(
                'FUND-%s-%s',
                $organizationCode,
                $datePrefix
            );

            /*
        |--------------------------------------------------------------------------
        | GET LAST REFERENCE
        |--------------------------------------------------------------------------
        */

            $lastReference = FundTypeAmount::query()

                ->select([
                    'id',
                    'reference_no',
                ])

                ->where(
                    'reference_no',
                    'like',
                    "{$referencePrefix}-%"
                )

                ->lockForUpdate()

                ->latest('id')

                ->first();

            /*
        |--------------------------------------------------------------------------
        | GENERATE NEXT SEQUENCE
        |--------------------------------------------------------------------------
        */

            $nextSequence = 1;

            if (
                $lastReference &&
                preg_match(
                    '/(\d+)$/',
                    $lastReference->reference_no,
                    $matches
                )
            ) {

                $nextSequence = (
                    (int) $matches[1]
                ) + 1;
            }

            /*
        |--------------------------------------------------------------------------
        | FINAL REFERENCE NUMBER
        |--------------------------------------------------------------------------
        |
        | Example:
        | FUND-RW001-202605-0001
        |
        */

            $referenceNo = sprintf(
                '%s-%04d',
                $referencePrefix,
                $nextSequence
            );

            /*
        |--------------------------------------------------------------------------
        | CREATE FUNDING AMOUNT
        |--------------------------------------------------------------------------
        */

            $fundingAmount = FundTypeAmount::query()

                ->create([

                    'organization_id' => $validated['organization_id'],

                    'fund_type_id' => $validated['funding_type_id'],

                    'reference_no' => $referenceNo,

                    'amount' => $validated['amount'],

                    'funding_date' => $validated['funding_date'],

                    'description' => $validated['description'] ?? null,

                    'is_active' => $validated['is_active'],
                ]);

            /*
        |--------------------------------------------------------------------------
        | COMMIT TRANSACTION
        |--------------------------------------------------------------------------
        */

            DB::commit();

            /*
        |--------------------------------------------------------------------------
        | SUCCESS RESPONSE
        |--------------------------------------------------------------------------
        */

            return response()->json([

                'success' => true,

                'message' => 'Funding amount berhasil ditambahkan.',

                'data' => [

                    'id' => $fundingAmount->id,

                    'reference_no' => $fundingAmount->reference_no,

                    'organization_id' => $fundingAmount->organization_id,

                    'fund_type_id' => $fundingAmount->fund_type_id,

                    'amount' => $fundingAmount->amount,

                    'funding_date' => $fundingAmount->funding_date,

                    'is_active' => $fundingAmount->is_active,

                    'created_at' => $fundingAmount->created_at,
                ],
            ], 201);
        } catch (ValidationException $e) {

            DB::rollBack();

            throw $e;
        } catch (Throwable $e) {

            DB::rollBack();

            /*
        |--------------------------------------------------------------------------
        | LOG ERROR
        |--------------------------------------------------------------------------
        */

            Log::error(

                'Failed create funding amount',

                [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => $e->getTraceAsString(),
                ]
            );

            /*
        |--------------------------------------------------------------------------
        | ERROR RESPONSE
        |--------------------------------------------------------------------------
        */

            return response()->json([

                'success' => false,

                'message' => 'Terjadi kesalahan saat menyimpan funding amount.',
            ], 500);
        }
    }
}
