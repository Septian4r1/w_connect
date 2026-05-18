<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'level',
        'type',
        'normal_balance',
        'is_header',
        'is_active',
        'description',
        'sort_order',
    ];

    /*
    |-----------------------------------------
    | RELASI PARENT
    |-----------------------------------------
    */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /*
    |-----------------------------------------
    | RELASI CHILDREN
    |-----------------------------------------
    */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('sort_order');
    }

    /*
    |-----------------------------------------
    | RECURSIVE TREE (PENTING UNTUK UI)
    |-----------------------------------------
    */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
