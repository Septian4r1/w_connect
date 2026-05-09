<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

trait FilterByWilayah
{
    protected static function bootFilterByWilayah()
    {
        static::addGlobalScope('wilayah', function (Builder $query) {

            if (!Auth::check()) return;

            /** @var User $user */
            $user = Auth::user();

            if ($user->hasRole('super_admin')) return;

            $wilayah = $user->getWilayahIds();

            $rwIds = $wilayah['rw_ids'];
            $rtIds = $wilayah['rt_ids'];

            if ($rtIds->isNotEmpty()) {
                $query->whereIn('rt_id', $rtIds);
                return;
            }

            if ($rwIds->isNotEmpty()) {
                $query->whereIn('rw_id', $rwIds);
                return;
            }

            $query->whereRaw('1 = 0');
        });
    }

    public function scopeByWilayah(Builder $query)
    {
        if (!Auth::check()) return $query;

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('super_admin')) return $query;

        $wilayah = $user->getWilayahIds();

        if ($wilayah['rt_ids']->isNotEmpty()) {
            return $query->whereIn('rt_id', $wilayah['rt_ids']);
        }

        if ($wilayah['rw_ids']->isNotEmpty()) {
            return $query->whereIn('rw_id', $wilayah['rw_ids']);
        }

        return $query->whereRaw('1 = 0');
    }
}
