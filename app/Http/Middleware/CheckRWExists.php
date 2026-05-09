<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\RW;
use App\Models\RT;
use App\Models\Block;

class CheckRWExists
{
    public function handle(Request $request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil status wilayah dari cache
        |--------------------------------------------------------------------------
        */
        $wilayahStatus = Cache::remember('wilayah.status', 60, function () {
            return [
                'rw' => RW::where('status', 'aktif')->exists(),
                'rt' => RT::where('status', 'aktif')->exists(),
                'block' => Block::where('status', 'aktif')->exists(),
            ];
        });

        $rwExists = $wilayahStatus['rw'];
        $rtExists = $wilayahStatus['rt'];
        $blockExists = $wilayahStatus['block'];

        /*
        |--------------------------------------------------------------------------
        | Logika tampil modal bertahap
        |--------------------------------------------------------------------------
        */
        $showRW = !$rwExists;
        $showRT = $rwExists && !$rtExists;
        $showBlock = $rtExists && !$blockExists;

        /*
        |--------------------------------------------------------------------------
        | Kirim ke request
        |--------------------------------------------------------------------------
        */
        $request->attributes->set('show_rw_modal', $showRW);
        $request->attributes->set('show_rt_modal', $showRT);
        $request->attributes->set('show_block_modal', $showBlock);

        return $next($request);
    }
}
