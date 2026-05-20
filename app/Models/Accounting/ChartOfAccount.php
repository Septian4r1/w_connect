<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'chart_of_accounts';

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
        | TREE STRUCTURE
        |--------------------------------------------------------------------------
        */
        'parent_id',
        'parent_path',
        'level',

        /*
        |--------------------------------------------------------------------------
        | ACCOUNT IDENTITY
        |--------------------------------------------------------------------------
        */
        'code',
        'name',

        /*
        |--------------------------------------------------------------------------
        | ACCOUNT CONFIGURATION
        |--------------------------------------------------------------------------
        */
        'type',
        'normal_balance',
        'currency',

        /*
        |--------------------------------------------------------------------------
        | BALANCE
        |--------------------------------------------------------------------------
        */
        'opening_balance',

        /*
        |--------------------------------------------------------------------------
        | FLAGS
        |--------------------------------------------------------------------------
        */
        'is_header',
        'is_postable',
        'is_active',

        /*
        |--------------------------------------------------------------------------
        | EXTRA
        |--------------------------------------------------------------------------
        */
        'description',
        'sort_order',
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
        'is_header'   => 'boolean',
        'is_postable' => 'boolean',
        'is_active'   => 'boolean',

        /*
        |--------------------------------------------------------------------------
        | INTEGER
        |--------------------------------------------------------------------------
        */
        'level'      => 'integer',
        'sort_order' => 'integer',

        /*
        |--------------------------------------------------------------------------
        | DECIMAL
        |--------------------------------------------------------------------------
        */
        'opening_balance' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ATTRIBUTE VALUES
    |--------------------------------------------------------------------------
    */
    protected $attributes = [

        'level'           => 1,
        'currency'        => 'IDR',
        'opening_balance' => 0,
        'is_header'       => false,
        'is_postable'     => true,
        'is_active'       => true,
        'sort_order'      => 0,
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Parent account
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    /**
     * Child accounts
     */
    public function children(): HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        )
            ->orderBy('sort_order')
            ->orderBy('code');
    }

    /**
     * Recursive child accounts
     */
    public function childrenRecursive(): HasMany
    {
        return $this->children()
            ->with('childrenRecursive');
    }

    /*
    |--------------------------------------------------------------------------
    | FUND ACCOUNT MAPPINGS
    |--------------------------------------------------------------------------
    */

    /**
     * Cash account mappings
     */
    public function cashFundMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'cash_account_id'
        );
    }

    /**
     * Revenue account mappings
     */
    public function revenueFundMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'revenue_account_id'
        );
    }

    /**
     * Expense account mappings
     */
    public function expenseFundMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'expense_account_id'
        );
    }

    /**
     * Payable account mappings
     */
    public function payableFundMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'payable_account_id'
        );
    }

    /**
     * Receivable account mappings
     */
    public function receivableFundMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'receivable_account_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope active accounts
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
     * Scope by account type
     */
    public function scopeType(
        Builder $query,
        string $type
    ): Builder {
        return $query->where(
            'type',
            $type
        );
    }

    /**
     * Scope postable accounts
     */
    public function scopePostable(
        Builder $query
    ): Builder {
        return $query->where(
            'is_postable',
            true
        );
    }

    /**
     * Scope header accounts
     */
    public function scopeHeader(
        Builder $query
    ): Builder {
        return $query->where(
            'is_header',
            true
        );
    }

    /**
     * Scope leaf accounts
     */
    public function scopeLeaf(
        Builder $query
    ): Builder {
        return $query->where(
            'is_header',
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT LOGIC (ERP RULES)
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        /*
        |--------------------------------------------------------------------------
        | CREATING
        |--------------------------------------------------------------------------
        */
        static::creating(function ($model) {

            /*
            |--------------------------------------------------------------------------
            | TREE STRUCTURE
            |--------------------------------------------------------------------------
            */
            if ($model->parent_id) {

                $parent = self::find($model->parent_id);

                if ($parent) {

                    $model->level = $parent->level + 1;

                    $model->parent_path = $parent->parent_path
                        ? $parent->parent_path . '/' . $parent->id
                        : (string) $parent->id;
                }
            } else {

                $model->level = 1;
                $model->parent_path = null;
            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL BALANCE AUTO DETECTION
            |--------------------------------------------------------------------------
            */
            if (!$model->normal_balance) {

                $model->normal_balance = match ($model->type) {

                    'asset',
                    'expense'
                    => 'debit',

                    'liability',
                    'equity',
                    'revenue'
                    => 'credit',

                    default
                    => 'debit'
                };
            }

            /*
            |--------------------------------------------------------------------------
            | POSTABLE RULE
            |--------------------------------------------------------------------------
            |
            | Header tidak boleh transaksi
            |
            */
            $model->is_postable = !$model->is_header;

            /*
            |--------------------------------------------------------------------------
            | DEFAULT VALUES
            |--------------------------------------------------------------------------
            */
            $model->currency = $model->currency ?? 'IDR';

            $model->opening_balance =
                $model->opening_balance ?? 0;
        });

        /*
        |--------------------------------------------------------------------------
        | UPDATING
        |--------------------------------------------------------------------------
        */
        static::updating(function ($model) {

            /*
            |--------------------------------------------------------------------------
            | HEADER RULE
            |--------------------------------------------------------------------------
            */
            $model->is_postable = !$model->is_header;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if account is leaf
     */
    public function isLeaf(): bool
    {
        return !$this->is_header;
    }

    /**
     * Check if account can transact
     */
    public function canTransact(): bool
    {
        return $this->is_postable;
    }

    /**
     * Check if account is active
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get full hierarchical code
     */
    public function getFullCodeAttribute(): string
    {
        return $this->parent_path
            ? $this->parent_path . '/' . $this->code
            : $this->code;
    }

    /**
     * Get readable account label
     */
    public function getAccountLabelAttribute(): string
    {
        return $this->code . ' - ' . $this->name;
    }
}
