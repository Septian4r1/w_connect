<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Rumah;
use App\Models\PengajuanPerubahan;

class PesanWargaController extends Controller
{
    /*
    ==========================================
    HALAMAN PESAN / STATUS PENGAJUAN
    ==========================================
    Menampilkan list notifikasi dan pengajuan warga
    ==========================================
    */
    public function index()
    {
        $rumahId = session('rumah_id');

        if (!$rumahId) {
            return redirect()->route('homeWarga');
        }

        $rumah = Rumah::find($rumahId);

        // -----------------------------
        // Ambil semua notifikasi rumah
        // -----------------------------
        $notifications = $rumah
            ? $rumah->notifications()->latest()->get()
            : collect([]);

        // -----------------------------
        // Ambil pengajuan terkait rumah
        // -----------------------------
        $pengajuanList = PengajuanPerubahan::with([
            'approvals' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }
        ])
            ->whereHas('warga.keluarga', function ($q) use ($rumahId) {
                $q->where('rumah_id', $rumahId);
            })
            ->latest()
            ->get()
            ->map(function ($item) use ($notifications) {

                // approval terakhir
                $lastApproval = $item->approvals->last()?->created_at;

                // cari notifikasi yang terkait dengan pengajuan ini
                $notif = $notifications->first(function ($n) use ($item) {
                    return ($n->data['no_pengajuan'] ?? null) == $item->no_pengajuan;
                });

                // tandai pesan baru
                $item->is_new = $notif && $notif->read_at === null;

                // ada update status setelah dibaca
                $item->has_update =
                    $notif &&
                    $notif->read_at &&
                    $lastApproval &&
                    $lastApproval->gt($notif->read_at);

                return $item;
            });

        return view('frontend.pesan_warga.index', compact(
            'notifications',
            'pengajuanList'
        ));
    }

    /*
    ==========================================
    MARK AS READ + REDIRECT / AJAX RESPONSE
    ==========================================
    Fungsi ini menangani:
    1️⃣ Tandai notifikasi sudah dibaca
    2️⃣ Response JSON jika AJAX (misal klik accordion)
    3️⃣ Redirect ke pengajuan terkait jika ada
    ==========================================
    */
    public function show($id, Request $request)
    {
        // -----------------------------
        // 1️⃣ Ambil rumah dari session
        // -----------------------------
        $rumahId = session('rumah_id');
        $rumah = Rumah::find($rumahId);

        if (!$rumah) {
            // Jika tidak ada rumah → redirect ke home warga
            if ($request->ajax()) {
                return response()->json(['success' => false]);
            }
            return redirect()->route('homeWarga');
        }

        // -----------------------------
        // 2️⃣ Dekripsi ID notifikasi
        // -----------------------------
        try {
            $id = Crypt::decryptString($id);
        } catch (\Exception $e) {
            // Jika gagal dekripsi → AJAX response false atau redirect
            if ($request->ajax()) {
                return response()->json(['success' => false]);
            }
            return redirect()->route('pesanWarga');
        }

        // -----------------------------
        // 3️⃣ Ambil notifikasi sesuai ID
        // -----------------------------
        $notif = $rumah->notifications()->where('id', $id)->first();

        if (!$notif) {
            // Jika notifikasi tidak ditemukan
            if ($request->ajax()) {
                return response()->json(['success' => false]);
            }
            return redirect()->route('pesanWarga');
        }

        // -----------------------------
        // 4️⃣ Tandai notifikasi sudah dibaca
        // -----------------------------
        if (is_null($notif->read_at)) {
            $notif->markAsRead();
        }

        // -----------------------------
        // 5️⃣ Jika request via AJAX → return JSON sukses
        // -----------------------------
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        // -----------------------------
        // 6️⃣ Redirect ke pengajuan terkait
        // -----------------------------
        $noPengajuan = $notif->data['no_pengajuan'] ?? null;

        if ($noPengajuan) {
            $pengajuan = PengajuanPerubahan::where('no_pengajuan', $noPengajuan)->first();
            $pengajuanId = $pengajuan?->id;

            if ($pengajuanId) {
                // Redirect ke halaman pesan dengan query param ?open=ID
                return redirect('/management/pesan?open=' . $pengajuanId);
            }
        }

        // -----------------------------
        // 7️⃣ Default redirect ke halaman pesan
        // -----------------------------
        return redirect()->route('pesanWarga');
    }
}
