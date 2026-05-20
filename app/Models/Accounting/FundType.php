<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class FundType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
