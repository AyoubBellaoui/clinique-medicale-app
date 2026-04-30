<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show_dashboard() {

        return view('dashboard.index');
    }
}
