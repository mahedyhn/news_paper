<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Newspapers;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('backend.dashboard.index');
    }
}
