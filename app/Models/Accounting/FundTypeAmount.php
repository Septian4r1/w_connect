<?php

namespace App\Models\Accounting;

use App\Models\Accounting\FundType as AccountingFundType;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundTypeAmount extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    protected $table = 'fund_type_amounts';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'organization_id',
        'fund_type_id',

        'reference_no',

        'amount',
        'funding_date',

        'is_active',
        'description',

        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [

        'amount' => 'decimal:2',

        'is_active' => 'boolean',

        'funding_date' => 'date',
    ];
    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function fundType(): BelongsTo
    {
        return $this->belongsTo(
            AccountingFundType::class,
            'fund_type_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE : ACTIVE
    |--------------------------------------------------------------------------
    */

    public function scopeActive(
        Builder $query
    ): Builder {

        return $query->where(
            'is_active',
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR : FORMATTED AMOUNT
    |--------------------------------------------------------------------------
    */

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format(
            $this->amount,
            0,
            ',',
            '.'
        );
    }
}
