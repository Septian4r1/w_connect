<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;

class IplController extends Controller
{
    public function index()
    {
        return view('frontend.management.ipl');
    }
}
