<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $church = $user->getCurrentChurch();
        
        if (!$church) {
            return redirect()->route('church.selection')->with('error', 'Aucune église associée à votre compte.');
        }

        return view('subscriptions.index', compact('church'));
    }
}