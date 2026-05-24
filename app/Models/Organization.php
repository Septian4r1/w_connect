<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\AccountingPeriod;

class Organization extends Model
{
    protected $table = 'organizations';

    protected $fillable = [
        'type',
        'code',
        'name',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | PARENT
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
    | CHILDREN
    |--------------------------------------------------------------------------
    */

    public function children(): HasMany
    {
        return $this->hasMany(
            self::class,
            'parent_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHILDREN RECURSIVE
    |--------------------------------------------------------------------------
    */

    public function childrenRecursive(): HasMany
    {
        return $this->children()
            ->with('childrenRecursive');
    }

    /*
    |--------------------------------------------------------------------------
    | PENGURUS
    |--------------------------------------------------------------------------
    */

    public function pengurus(): HasMany
    {
        return $this->hasMany(
            PengurusWilayah::class,
            'organization_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
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

    public function scopeRw(
        Builder $query
    ): Builder {

        return $query->where(
            'type',
            'rw'
        );
    }

    public function scopeRt(
        Builder $query
    ): Builder {

        return $query->where(
            'type',
            'rt'
        );
    }

    public function scopeVendor(
        Builder $query
    ): Builder {

        return $query->where(
            'type',
            'vendor'
        );
    }

    public function scopeLembaga(
        Builder $query
    ): Builder {

        return $query->where(
            'type',
            'lembaga'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isRw(): bool
    {
        return $this->type === 'rw';
    }

    public function isRt(): bool
    {
        return $this->type === 'rt';
    }

    public function isVendor(): bool
    {
        return $this->type === 'vendor';
    }

    public function isLembaga(): bool
    {
        return $this->type === 'lembaga';
    }

    /*
    |--------------------------------------------------------------------------
    | HAS PARENT
    |--------------------------------------------------------------------------
    */

    public function hasParent(): bool
    {
        return !empty($this->parent_id);
    }

    /*
    |--------------------------------------------------------------------------
    | IS ROOT ORGANIZATION
    |--------------------------------------------------------------------------
    */

    public function isRoot(): bool
    {
        return empty($this->parent_id);
    }

    /*
    |--------------------------------------------------------------------------
    | GET FULL NAME
    |--------------------------------------------------------------------------
    */

    public function fullName(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /*
|--------------------------------------------------------------------------
| ACCOUNTING PERIODS
|--------------------------------------------------------------------------
*/

    public function accountingPeriods(): HasMany
    {
        return $this->hasMany(
            AccountingPeriod::class,
            'organization_id'
        );
    }
}
