<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacturesController extends Controller
{
    public function index()
    {
        return view('billing.index');
    }

    public function create()
    {
        return view('billing.create');
    }
}
