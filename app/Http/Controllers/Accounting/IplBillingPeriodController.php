<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use App\Services\Billing\IplBillingPeriodService;
use App\Models\Organization;
use App\Models\Accounting\AccountingPeriod;
use App\Models\Accounting\IplBillingPeriod;

class IplBillingPeriodController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function __construct(
        private IplBillingPeriodService $service
    ) {}

    public function index(Request $request): View
    {


        $query = IplBillingPeriod::query()
            ->with([
                'organization:id,code,name,type',
                'accountingPeriod:id,code,name,year,month,status',
                'creator:id,name',
            ]);



        $query->when(
            $request->filled('organization_id'),
            fn($q) =>
            $q->where(
                'organization_id',
                $request->organization_id
            )
        );


        $query->when(
            $request->filled('accounting_period_id'),
            fn($q) =>
            $q->where(
                'accounting_period_id',
                $request->accounting_period_id
            )
        );


        $query->when(
            $request->filled('status'),
            fn($q) =>
            $q->where(
                'status',
                $request->status
            )
        );


        $query->when(
            $request->filled('billing_type'),
            fn($q) =>
            $q->where(
                'billing_type',
                $request->billing_type
            )
        );


        $query->when(
            $request->filled('search'),
            function ($q) use ($request) {

                $search = trim($request->search);

                $q->where(function ($sub) use ($search) {

                    $sub->where(
                        'code',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                });
            }
        );
        $query->orderByDesc('invoice_date')
            ->orderByDesc('id');

        $billingPeriods = $query
            ->paginate(20)
            ->withQueryString();


        $organizations = Organization::query()
            ->select([
                'id',
                'code',
                'name',
                'type'
            ])
            ->active()
            ->orderBy('code')
            ->get();

        $accountingPeriods = AccountingPeriod::query()
            ->select([
                'id',
                'code',
                'name',
                'year',
                'month',
                'organization_id'
            ])
            ->latestFirst()
            ->get();


        $summary = [
            'total_periods' => IplBillingPeriod::count(),
            'open_periods' => IplBillingPeriod::open()->count(),
            'closed_periods' => IplBillingPeriod::closed()->count(),
            'total_amount' => IplBillingPeriod::sum('total_amount'),
            'total_paid' => IplBillingPeriod::sum('total_paid'),
            'total_unpaid' => IplBillingPeriod::sum('total_unpaid'),
        ];

        return view(
            'backend.accounting.ipl_billing_periods.index',
            compact(
                'billingPeriods',
                'organizations',
                'accountingPeriods',
                'summary'
            )
        );
    }


    public function create(): View
    {


        $organizations = Organization::query()
            ->select([
                'id',
                'code',
                'name',
                'type'
            ])
            ->active()
            ->orderBy('code')
            ->get();


        $accountingPeriods = AccountingPeriod::query()
            ->select([
                'id',
                'code',
                'name',
                'year',
                'month',
                'organization_id',
                'status',
                'is_current',
            ])
            ->open()
            ->latestFirst()
            ->get();

        return view(
            'backend.accounting.ipl_billing_periods.create',
            compact(
                'organizations',
                'accountingPeriods'
            )
        );
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => ['required', 'exists:organizations,id'],
            'accounting_period_id' => ['required', 'exists:accounting_periods,id'],

            'code' => ['required', 'string', 'max:100', 'unique:ipl_billing_periods,code'],
            'name' => ['required', 'string', 'max:255'],

            'billing_type' => ['required', 'in:IPL,DENDA,KHUSUS,DLL'],
            'category' => ['required', 'in:REGULAR,RECURRING,SPECIAL'],

            'description' => ['nullable', 'string'],

            'invoice_date' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:invoice_date'],

            'grace_days' => ['nullable', 'integer', 'min:0'],

            'notes' => ['nullable', 'string'],
        ]);

        $accountingPeriod = AccountingPeriod::findOrFail(
            $validated['accounting_period_id']
        );

        if (! $accountingPeriod->canTransact()) {
            return back()->withErrors([
                'accounting_period_id' => 'Accounting period is locked or closed.'
            ])->withInput();
        }

        try {

            $billingPeriod = $this->service->create($validated);

            return redirect()
                ->route('accounting.ipl-billing-periods.show', $billingPeriod->id)
                ->with('success', 'Billing period created successfully.');
        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }



    public function show(
        IplBillingPeriod $iplBillingPeriod
    ): View {

        $iplBillingPeriod->load([

            'organization:id,code,name,type',

            'accountingPeriod:id,code,name,year,month,status',

            'creator:id,name',

            'closer:id,name',
        ]);

        return view(
            'backend.accounting.ipl_billing_periods.show',
            compact('iplBillingPeriod')
        );
    }
}
