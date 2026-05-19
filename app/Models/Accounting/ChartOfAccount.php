<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'parent_id',
        'parent_path',
        'code',
        'name',
        'level',
        'type',
        'normal_balance',
        'opening_balance',
        'currency',
        'is_header',
        'is_postable',
        'is_active',
        'description',
        'sort_order',
    ];

    /*
    |-----------------------------------------
    | RELATIONS
    |-----------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order')
            ->orderBy('code');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    /*
    |-----------------------------------------
    | SCOPES
    |-----------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopePostable(Builder $query): Builder
    {
        return $query->where('is_postable', 1);
    }

    public function scopeHeader(Builder $query): Builder
    {
        return $query->where('is_header', 1);
    }

    public function scopeLeaf(Builder $query): Builder
    {
        return $query->where('is_header', 0);
    }

    /*
    |-----------------------------------------
    | BOOT LOGIC (ERP RULES)
    |-----------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

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

            // normal balance
            if (!$model->normal_balance) {
                $model->normal_balance = match ($model->type) {
                    'asset', 'expense' => 'debit',
                    'liability', 'equity', 'revenue' => 'credit',
                    default => 'debit'
                };
            }

            // postable rule
            $model->is_postable = ($model->is_header == 0);

            $model->currency = $model->currency ?? 'IDR';
            $model->opening_balance = $model->opening_balance ?? 0;
        });

        static::updating(function ($model) {
            $model->is_postable = ($model->is_header == 0);
        });
    }

    /*
    |-----------------------------------------
    | HELPERS
    |-----------------------------------------
    */

    public function isLeaf(): bool
    {
        return $this->is_header == 0;
    }

    public function canTransact(): bool
    {
        return $this->is_postable == 1;
    }

    public function getFullCodeAttribute(): string
    {
        return $this->parent_path
            ? $this->parent_path . '/' . $this->code
            : $this->code;
    }
}
