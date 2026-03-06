<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;

class UnderConstructionController extends Controller
{
    public function index($title)
    {
        return view('backend.management.under_construction', [
            'title' => $title
        ]);
    }
}
