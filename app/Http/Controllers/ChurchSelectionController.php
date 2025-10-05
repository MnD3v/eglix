<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChurchSelectionController extends Controller
{
    /**
     * Show the church selection page
     */
    public function index()
    {
        $user = Auth::user();
        $churches = $user->activeChurches()->get();
        
        return view('church-selection', compact('churches'));
    }
}
