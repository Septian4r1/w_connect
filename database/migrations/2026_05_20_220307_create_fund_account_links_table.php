<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Organization;
use App\Models\Accounting\FundType;
use App\Models\Accounting\AccountRole;
use App\Models\Accounting\ChartOfAccount;

class FundAccountLink extends Model
{
    /*
    |----------------------------------------------------------------------
    | TABLE
    |----------------------------------------------------------------------
    */
    protected $table = 'fund_account_links';

    /*
    |----------------------------------------------------------------------
    | FILLABLE (MATCH MIGRATION)
    |----------------------------------------------------------------------
    */
    protected $fillable = [
        'fund_type_id',
        'coa_id',
        'account_role_id',
        'organization_id',

        'priority',
        'is_default',
        'is_active',
    ];

    /*
    |----------------------------------------------------------------------
    | CASTS
    |----------------------------------------------------------------------
    */
    protected $casts = [
        'priority'   => 'integer',
        'is_default' => 'boolean',
        'is_active'  => 'boolean',
    ];

    /*
    |----------------------------------------------------------------------
    | APPENDS
    |----------------------------------------------------------------------
    */
    protected $appends = [
        'role_code',
        'organization_label',
    ];

    /*
    |----------------------------------------------------------------------
    | RELATION: FUND TYPE
    |----------------------------------------------------------------------
    */
    public function fundType(): BelongsTo
    {
        return $this->belongsTo(FundType::class);
    }

    /*
    |----------------------------------------------------------------------
    | RELATION: COA
    |----------------------------------------------------------------------
    */
    public function coa(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    /*
    |----------------------------------------------------------------------
    | RELATION: ACCOUNT ROLE
    |----------------------------------------------------------------------
    */
    public function accountRole(): BelongsTo
    {
        return $this->belongsTo(AccountRole::class);
    }

    /*
    |----------------------------------------------------------------------
    | RELATION: ORGANIZATION (FIXED)
    |----------------------------------------------------------------------
    */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /*
    |----------------------------------------------------------------------
    | ACCESSOR: ORGANIZATION LABEL
    |----------------------------------------------------------------------
    */
    public function getOrganizationLabelAttribute(): string
    {
        return $this->organization?->name
            ?? $this->organization?->code
            ?? '-';
    }

    /*
    |----------------------------------------------------------------------
    | ACCESSOR: ROLE CODE
    |----------------------------------------------------------------------
    */
    public function getRoleCodeAttribute(): ?string
    {
        return $this->accountRole?->code;
    }

    /*
    |----------------------------------------------------------------------
    | SCOPES
    |----------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', 1);
    }

    public function scopeByFund(Builder $query, int $fundTypeId): Builder
    {
        return $query->where('fund_type_id', $fundTypeId);
    }

    public function scopeByRole(Builder $query, string $roleCode): Builder
    {
        return $query->whereHas('accountRole', function ($q) use ($roleCode) {
            $q->where('code', $roleCode);
        });
    }

    public function scopeByOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->where('organization_id', $organizationId);
    }

    /*
    |----------------------------------------------------------------------
    | CORE RESOLVE ENGINE (UPDATED)
    |----------------------------------------------------------------------
    |
    | Ini pengganti scope_type/scope_id logic lama
    | sekarang full relational organization-based
    |
    */
    public static function resolve(
        int $fundTypeId,
        string $roleCode,
        int $organizationId
    ): ?self {

        return self::query()
            ->with([
                'coa',
                'accountRole',
                'fundType',
                'organization',
            ])
            ->active()
            ->byFund($fundTypeId)
            ->byRole($roleCode)
            ->byOrganization($organizationId)
            ->orderByDesc('priority')
            ->first();
    }
}
