<?php

namespace App\Models\Accounting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FundAccountMapping extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'fund_account_mappings';

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */
    protected $primaryKey = 'id';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | FUND TYPE
        |--------------------------------------------------------------------------
        */
        'fund_type_id',

        /*
        |--------------------------------------------------------------------------
        | ACCOUNT MAPPINGS
        |--------------------------------------------------------------------------
        */
        'cash_account_id',
        'revenue_account_id',
        'expense_account_id',
        'payable_account_id',
        'receivable_account_id',

        /*
        |--------------------------------------------------------------------------
        | STATUS & FLAGS
        |--------------------------------------------------------------------------
        */
        'is_default',
        'is_active',

        /*
        |--------------------------------------------------------------------------
        | NOTES
        |--------------------------------------------------------------------------
        */
        'notes',

        /*
        |--------------------------------------------------------------------------
        | AUDIT
        |--------------------------------------------------------------------------
        */
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE CASTING
    |--------------------------------------------------------------------------
    */
    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | BOOLEAN
        |--------------------------------------------------------------------------
        */
        'is_default' => 'boolean',
        'is_active'  => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | DATETIME
        |--------------------------------------------------------------------------
        */
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ATTRIBUTE VALUES
    |--------------------------------------------------------------------------
    */
    protected $attributes = [

        'is_default' => true,
        'is_active'  => true,
    ];

    /*
    |--------------------------------------------------------------------------
    | FUND TYPE RELATION
    |--------------------------------------------------------------------------
    */

    /**
     * Related fund type
     */
    public function fundType(): BelongsTo
    {
        return $this->belongsTo(
            FundType::class,
            'fund_type_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHART OF ACCOUNT RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Cash account
     */
    public function cashAccount(): BelongsTo
    {
        return $this->belongsTo(
            ChartOfAccount::class,
            'cash_account_id'
        );
    }

    /**
     * Revenue account
     */
    public function revenueAccount(): BelongsTo
    {
        return $this->belongsTo(
            ChartOfAccount::class,
            'revenue_account_id'
        );
    }

    /**
     * Expense account
     */
    public function expenseAccount(): BelongsTo
    {
        return $this->belongsTo(
            ChartOfAccount::class,
            'expense_account_id'
        );
    }

    /**
     * Payable account
     */
    public function payableAccount(): BelongsTo
    {
        return $this->belongsTo(
            ChartOfAccount::class,
            'payable_account_id'
        );
    }

    /**
     * Receivable account
     */
    public function receivableAccount(): BelongsTo
    {
        return $this->belongsTo(
            ChartOfAccount::class,
            'receivable_account_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AUDIT RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Creator user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Updater user
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope active mappings
     */
    public function scopeActive(
        Builder $query
    ): Builder {
        return $query->where(
            'is_active',
            true
        );
    }

    /**
     * Scope default mappings
     */
    public function scopeDefault(
        Builder $query
    ): Builder {
        return $query->where(
            'is_default',
            true
        );
    }

    /**
     * Scope by fund type
     */
    public function scopeFund(
        Builder $query,
        int $fundTypeId
    ): Builder {
        return $query->where(
            'fund_type_id',
            $fundTypeId
        );
    }

    /**
     * Scope active default mappings
     */
    public function scopeActiveDefault(
        Builder $query
    ): Builder {
        return $query
            ->where('is_active', true)
            ->where('is_default', true);
    }

    /**
     * Scope mappings with complete setup
     */
    public function scopeComplete(
        Builder $query
    ): Builder {
        return $query
            ->whereNotNull('cash_account_id')
            ->whereNotNull('revenue_account_id')
            ->whereNotNull('expense_account_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check active status
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Check default status
     */
    public function isDefault(): bool
    {
        return (bool) $this->is_default;
    }

    /**
     * Check if setup is complete
     */
    public function hasCompleteAccounts(): bool
    {
        return !empty($this->cash_account_id)
            && !empty($this->revenue_account_id)
            && !empty($this->expense_account_id);
    }

    /**
     * Check if payable account exists
     */
    public function hasPayableAccount(): bool
    {
        return !empty($this->payable_account_id);
    }

    /**
     * Check if receivable account exists
     */
    public function hasReceivableAccount(): bool
    {
        return !empty($this->receivable_account_id);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Human readable status
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active
            ? 'Active'
            : 'Inactive';
    }

    /**
     * Human readable default label
     */
    public function getDefaultLabelAttribute(): string
    {
        return $this->is_default
            ? 'Default'
            : 'Non Default';
    }

    /**
     * Human readable mapping label
     */
    public function getMappingLabelAttribute(): string
    {
        return $this->fundType?->code . ' - '
            . $this->fundType?->name;
    }
}
