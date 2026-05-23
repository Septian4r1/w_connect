<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountRole extends Model
{
    use SoftDeletes;

    protected $table = 'account_roles';

    protected $fillable = [

        'code',
        'name',
        'description',

        'coa_type',
        'normal_balance',

        'is_system',
        'is_active',
    ];

    protected $casts = [

        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function fundAccountLinks(): HasMany
    {
        return $this->hasMany(FundAccountLink::class);
    }
}
