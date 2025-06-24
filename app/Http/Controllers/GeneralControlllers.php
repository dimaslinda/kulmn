<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralControlllers extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function mitra()
    {
        return view('mitra');
    }
}
