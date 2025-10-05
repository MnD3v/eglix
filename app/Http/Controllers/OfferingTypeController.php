<?php

namespace App\Http\Controllers;

use App\Models\OfferingType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OfferingTypeController extends Controller
{
    public function index()
    {
        $types = OfferingType::where(function($q){
                $q->whereNull('church_id')->orWhere('church_id', get_current_church_id());
            })
            ->orderBy('is_active','desc')
            ->orderBy('name')
            ->get();
        return view('offering-types.index', compact('types'));
    }

    public function create()
    {
        return view('offering-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100'],
            'slug' => ['nullable','string','max:100','unique:offering_types,slug'],
            'color' => ['nullable','string','max:20'],
            'is_active' => ['nullable','boolean'],
        ]);
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name'], '_');
        $validated['is_active'] = (bool)($validated['is_active'] ?? true);
        $validated['church_id'] = get_current_church_id();
        OfferingType::create($validated);
        return redirect()->route('offering-types.index')->with('success','Type créé.');
    }

    public function edit(OfferingType $offering_type)
    {
        return view('offering-types.edit', ['type' => $offering_type]);
    }

    public function update(Request $request, OfferingType $offering_type)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:100'],
            'slug' => ['required','string','max:100','unique:offering_types,slug,'.$offering_type->id],
            'color' => ['nullable','string','max:20'],
            'is_active' => ['nullable','boolean'],
        ]);
        $validated['is_active'] = (bool)($validated['is_active'] ?? false);
        $offering_type->update($validated);
        return redirect()->route('offering-types.index')->with('success','Type mis à jour.');
    }

    public function destroy(OfferingType $offering_type)
    {
        $offering_type->delete();
        return redirect()->route('offering-types.index')->with('success','Type supprimé.');
    }

    public function toggle(OfferingType $offering_type)
    {
        $offering_type->is_active = ! $offering_type->is_active;
        $offering_type->save();
        return redirect()->route('offering-types.index');
    }
}


