<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Movement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $movements = Movement::distinct('asset_id')->latest('created_at')->get();
        return view('dashboard', compact('movements'));
    }
}
