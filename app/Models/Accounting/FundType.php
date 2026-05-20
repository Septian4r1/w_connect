<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FundType extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'fund_types';

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
        | FUND IDENTITY
        |--------------------------------------------------------------------------
        */
        'code',
        'name',

        /*
        |--------------------------------------------------------------------------
        | EXTRA
        |--------------------------------------------------------------------------
        */
        'description',

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        'is_active',
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
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | DEFAULT ATTRIBUTE VALUES
    |--------------------------------------------------------------------------
    */
    protected $attributes = [

        'is_active' => true,
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * All account mappings
     */
    public function accountMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'fund_type_id'
        );
    }

    /**
     * Default account mapping
     */
    public function defaultAccountMapping(): HasOne
    {
        return $this->hasOne(
            FundAccountMapping::class,
            'fund_type_id'
        )
            ->where('is_default', true)
            ->where('is_active', true);
    }

    /**
     * Active account mappings
     */
    public function activeAccountMappings(): HasMany
    {
        return $this->hasMany(
            FundAccountMapping::class,
            'fund_type_id'
        )
            ->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope active fund types
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
     * Scope by code
     */
    public function scopeCode(
        Builder $query,
        string $code
    ): Builder {
        return $query->where(
            'code',
            $code
        );
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
     * Check if default mapping exists
     */
    public function hasDefaultMapping(): bool
    {
        return $this->defaultAccountMapping()
            ->exists();
    }

    /**
     * Get readable fund label
     */
    public function getFundLabelAttribute(): string
    {
        return $this->code . ' - ' . $this->name;
    }
}
