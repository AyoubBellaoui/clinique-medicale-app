<?php

namespace App\Http\Controllers;

use App\Models\StaffMedical;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show_dashboard() {

        $staff = StaffMedical::all();

        return view('dashboard.index', compact('staff'));
    }
}
