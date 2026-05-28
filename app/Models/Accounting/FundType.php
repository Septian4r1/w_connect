<?php

namespace App\Models\Accounting;

use App\Models\FundAccountLink;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Accounting\FundTypeAmount;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FundType extends Model
{
    /*
    |----------------------------------------------------------
    | TABLE
    |----------------------------------------------------------
    */
    protected $table = 'fund_types';

    /*
    |----------------------------------------------------------
    | FILLABLE
    |----------------------------------------------------------
    */
    protected $fillable = [
        'code',        // kode dana (SMP, CSR, SOS)
        'name',        // nama dana
        'description', // keterangan
        'is_active',   // status aktif
    ];

    /*
    |----------------------------------------------------------
    | RELATION: ALL LINKS
    |----------------------------------------------------------
    | 1 FundType -> banyak COA mapping
    */
    public function fundAccountLinks(): HasMany
    {
        return $this->hasMany(FundAccountLink::class);
    }

    public function activeAccountLinks(): HasMany
    {
        return $this->hasMany(FundAccountLink::class, 'fund_type_id')
            ->where('is_active', true);
    }

    public function defaultAccountLink(): HasOne
    {
        return $this->hasOne(FundAccountLink::class, 'fund_type_id')
            ->where('is_default', true)
            ->where('is_active', true);
    }

    // ============================
    // INI TARUH DI SINI 👇
    // ============================

    public function coas()
    {
        return $this->belongsToMany(
            ChartOfAccount::class,
            'fund_account_links',
            'fund_type_id',
            'coa_id'
        )->withPivot([
            'role',
            'scope',
            'priority',
            'is_default',
            'is_active'
        ])->wherePivot('is_active', 1);
    }

    // ============================
    // OPTIONAL: GROUPING METHOD
    // ============================

    public function cashCoas()
    {
        return $this->belongsToMany(
            ChartOfAccount::class,
            'fund_account_links',
            'fund_type_id',
            'coa_id'
        )->wherePivot('role', 'cash')
            ->wherePivot('is_active', 1);
    }

    public function bankCoas()
    {
        return $this->belongsToMany(
            ChartOfAccount::class,
            'fund_account_links',
            'fund_type_id',
            'coa_id'
        )->wherePivot('role', 'bank')
            ->wherePivot('is_active', 1);
    }

    public function expenseCoas()
    {
        return $this->belongsToMany(
            ChartOfAccount::class,
            'fund_account_links',
            'fund_type_id',
            'coa_id'
        )->wherePivot('role', 'expense')
            ->wherePivot('is_active', 1);
    }

    /*
|--------------------------------------------------------------------------
| FUND TYPE AMOUNTS
|--------------------------------------------------------------------------
|
| 1 FundType -> banyak nominal IPL
| per organization
|
*/

    public function fundTypeAmounts(): HasMany
    {
        return $this->hasMany(
            FundTypeAmount::class,
            'fund_type_id'
        );
    }

    /*
|--------------------------------------------------------------------------
| ACTIVE FUND TYPE AMOUNTS
|--------------------------------------------------------------------------
*/

    public function activeFundTypeAmounts(): HasMany
    {
        return $this->fundTypeAmounts()
            ->where(
                'is_active',
                true
            );
    }
}
