<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeWargaController extends Controller
{
    public function HomeWarga()
    {
        return view('frontend.home_warga');
    }
}
