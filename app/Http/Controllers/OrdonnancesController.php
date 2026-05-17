<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdonnancesController extends Controller
{
    public function index()
    {
        return view('prescriptions.index');
    }

    public function create()
    {
        return view('prescriptions.create');
    }
}
