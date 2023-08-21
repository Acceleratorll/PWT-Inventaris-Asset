<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Models\Room;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function create()
    {
        $types = AssetType::all();
        $rooms = Room::all();
        return view('assets/create', compact(['types', 'rooms']));
    }
}
