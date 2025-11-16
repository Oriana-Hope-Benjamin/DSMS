<?php

namespace App\Http\Controllers;
use App\Models\Durations as DurationsModel;
use Illuminate\Http\Request;

class Durations extends Controller
{
    public function index()
    {
        $durations = DurationsModel::all();
        return response()->json($durations);
    }
}
