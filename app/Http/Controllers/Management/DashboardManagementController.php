<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardManagementController extends Controller
{
    /**
     * =====================================================
     * TAMPILKAN HALAMAN Dashboard
     * =====================================================
     */
    public function index()
    {
        return view('backend.management.dashboard.index');
    }
}
