<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class GeneralControlllers extends Controller
{
    public function index()
    {
        $branches = Branch::with('media')->get();
        return view('index', compact('branches'));
    }

    public function mitra()
    {
        return view('mitra');
    }

    public function academy()
    {
        return view('academy');
    }
}
