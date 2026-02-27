<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rumah;
use Illuminate\Support\Facades\Cache;

class LayananApprovalController extends Controller
{
    public function setuju(Request $request)
    {
        try {

            // 🔥 Ambil dari SESSION, bukan dari input
            $rumahId = $request->session()->get('rumah_id');

            if (!$rumahId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Session tidak valid.'
                ], 401);
            }

            $rumah = Rumah::find($rumahId);

            if (!$rumah) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data rumah tidak ditemukan.'
                ], 404);
            }

            $rumah->layanan_approval = 1;
            $rumah->save();

            // 🔥 Hapus cache supaya middleware update
            Cache::forget("layanan_approval_{$rumahId}");

            return response()->json([
                'status' => 'success',
                'message' => 'Persetujuan layanan berhasil disimpan.'
            ]);
        } catch (\Throwable $e) {

            \Log::error('Error setuju layanan: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan.'
            ], 500);
        }
    }
}
