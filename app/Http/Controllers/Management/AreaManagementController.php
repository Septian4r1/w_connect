<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\PengurusWilayah;
use App\Models\Rw;
use Illuminate\Http\Request;

class AreaManagementController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua RW aktif
        $rws = Rw::active()->get();

        // RW yang dipilih, default RW pertama
        $rwSelected = $request->input('rw_id', $rws->first()?->id ?? null);

        // Ambil RT list berdasarkan RW yang dipilih
        $rtList = [];
        if ($rwSelected) {
            $rtList = PengurusWilayah::with(['user', 'role'])
                ->where('rw_id', $rwSelected) // <-- gunakan ID RW yang dipilih
                ->orderBy('rt_id')
                ->get()
                ->groupBy('rt_id');
        }

        return view('backend.management.area_management.index_area', compact('rws', 'rwSelected', 'rtList'));
    }
}
