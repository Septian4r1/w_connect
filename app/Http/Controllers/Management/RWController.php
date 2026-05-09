<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\RW;
use App\Models\RT;
use App\Models\Block;

class RWController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STORE RW
    |--------------------------------------------------------------------------
    */
    public function storeRW(Request $request)
    {
        $validated = $request->validate([
            'nama_rw' => ['required', 'string', 'max:100'],
        ]);

        $namaRW = trim(strip_tags($validated['nama_rw']));

        try {
            DB::beginTransaction();

            $rwExists = RW::lockForUpdate()
                ->where('status', 'aktif')
                ->exists();

            if ($rwExists) {
                DB::rollBack();
                return $this->warning('RW Sudah Ada', 'RW aktif sudah terdaftar.');
            }

            RW::create([
                'nama_rw' => $namaRW,
                'status' => 'aktif',
            ]);

            Cache::forget('wilayah.status');

            DB::commit();

            return $this->success('RW berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error($e, $request);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE RT
    |--------------------------------------------------------------------------
    */
    public function storeRT(Request $request)
    {
        $validated = $request->validate([
            'rw_id' => ['required', 'exists:rws,id'],
            'nama_rt' => ['required', 'string', 'max:100'],
        ]);

        try {
            DB::beginTransaction();

            $rtExists = RT::lockForUpdate()
                ->where('status', 'aktif')
                ->exists();

            if ($rtExists) {
                DB::rollBack();
                return $this->warning('RT Sudah Ada', 'RT aktif sudah terdaftar.');
            }

            RT::create([
                'rw_id' => $validated['rw_id'],
                'nama_rt' => trim(strip_tags($validated['nama_rt'])),
                'status' => 'aktif',
            ]);

            Cache::forget('wilayah.status');

            DB::commit();

            return $this->success('RT berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error($e, $request);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE BLOCK
    |--------------------------------------------------------------------------
    */
    public function storeBlock(Request $request)
    {
        $validated = $request->validate([
            'rt_id' => ['required', 'exists:rts,id'],
            'nama_blok' => ['required', 'string', 'max:100'],
        ]);

        try {
            DB::beginTransaction();

            $blockExists = Block::lockForUpdate()
                ->where('status', 'aktif')
                ->exists();

            if ($blockExists) {
                DB::rollBack();
                return $this->warning('Block Sudah Ada', 'Block aktif sudah terdaftar.');
            }

            Block::create([
                'rt_id' => $validated['rt_id'],
                'nama_blok' => trim(strip_tags($validated['nama_blok'])),
                'status' => 'aktif',
            ]);

            Cache::forget('wilayah.status');

            DB::commit();

            return $this->success('Block berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->error($e, $request);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSE HELPER
    |--------------------------------------------------------------------------
    */
    private function success($message)
    {
        return redirect()->route('splash_management')->with([
            'alert_status' => 'success',
            'alert_title' => 'Berhasil',
            'alert_message' => $message
        ]);
    }

    private function warning($title, $message)
    {
        return redirect()->route('splash_management')->with([
            'alert_status' => 'warning',
            'alert_title' => $title,
            'alert_message' => $message
        ]);
    }

    private function error($e, $request)
    {
        Log::error('Setup wilayah gagal', [
            'error' => $e->getMessage(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('splash_management')->with([
            'alert_status' => 'error',
            'alert_title' => 'Terjadi Kesalahan',
            'alert_message' => 'Gagal menyimpan data.'
        ]);
    }
}
