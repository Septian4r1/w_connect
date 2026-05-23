<?php

namespace App\Models\Accounting;

use App\Models\FundAccountLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class ChartOfAccount extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'chart_of_accounts';

    /*
    |--------------------------------------------------------------------------
    | FILLABLE
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'parent_id',
        'parent_path',
        'level',

        'code',
        'name',

        'type',
        'normal_balance',

        'currency',
        'opening_balance',

        'is_header',
        'is_postable',
        'is_active',

        'description',
        'sort_order',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'opening_balance' => 'decimal:2',

        'is_header'   => 'boolean',
        'is_postable' => 'boolean',
        'is_active'   => 'boolean',

        'level'       => 'integer',
        'sort_order'  => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */
    protected $appends = [
        'label',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION: PARENT
    |--------------------------------------------------------------------------
    */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'parent_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION: CHILDREN
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | RELATION: CHILDREN RECURSIVE
    |--------------------------------------------------------------------------
    */
    public function childrenRecursive(): HasMany
    {
        return $this->children()
            ->with([
                'childrenRecursive'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION: FUND ACCOUNT LINKS
    |--------------------------------------------------------------------------
    | 1 COA bisa dipakai banyak fund type
    |--------------------------------------------------------------------------
    */
    public function fundAccountLinks(): HasMany
    {
        return $this->hasMany(
            FundAccountLink::class,
            'coa_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION: ACTIVE FUND LINKS
    |--------------------------------------------------------------------------
    */
    public function activeFundLinks(): HasMany
    {
        return $this->fundAccountLinks()
            ->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION: FUND LINKS WITH RELATIONS
    |--------------------------------------------------------------------------
    | Anti N+1
    |--------------------------------------------------------------------------
    */
    public function fundLinksFull(): HasMany
    {
        return $this->fundAccountLinks()
            ->with([
                'fundType',
                'accountRole',
                'scopeOrganization',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE: ACTIVE
    |--------------------------------------------------------------------------
    */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE: POSTABLE
    |--------------------------------------------------------------------------
    */
    public function scopePostable(Builder $query): Builder
    {
        return $query->where('is_postable', true);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPE: HEADER
    |--------------------------------------------------------------------------
    */
    public function scopeHeader(Builder $query): Builder
    {
        return $query->where('is_header', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: LABEL
    |--------------------------------------------------------------------------
    */
    public function getLabelAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: FULL PATH
    |--------------------------------------------------------------------------
    */
    public function getFullPathAttribute(): string
    {
        return trim(
            "{$this->code} - {$this->name}",
            ' - '
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: IS ROOT
    |--------------------------------------------------------------------------
    */
    public function getIsRootAttribute(): bool
    {
        return is_null($this->parent_id);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: HAS CHILDREN
    |--------------------------------------------------------------------------
    */
    public function getHasChildrenAttribute(): bool
    {
        return $this->children()->exists();
    }
}
