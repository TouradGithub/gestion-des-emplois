<?php

namespace App\Http\Controllers;

use App\Models\Trimester;
use App\Models\NiveauPedagogique;
use Illuminate\Http\Request;

class TrimesterController extends Controller
{
    public function index()
    {
        $trimesters = Trimester::with('niveau')->latest()->get();
        return view('admin.trimesters.index', compact('trimesters'));
    }

    public function create()
    {
        $niveaux = NiveauPedagogique::all();
        return view('admin.trimesters.create', compact('niveaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
        ]);

        Trimester::create($request->only('name', 'niveau_pedagogique_id'));

        return redirect()->route('web.trimesters.index')->with('success', 'Trimester created successfully.');
    }

    public function edit(Trimester $trimester)
    {
        $niveaux = NiveauPedagogique::all();
        return view('admin.trimesters.edit', compact('trimester', 'niveaux'));
    }

    public function update(Request $request, Trimester $trimester)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
        ]);

        $trimester->update($request->only('name', 'niveau_pedagogique_id'));

        return redirect()->route('web.trimesters.index')->with('success', 'Trimester updated successfully.');
    }

    public function destroy(Trimester $trimester)
    {
        $trimester->delete();

        return redirect()->route('web.trimesters.index')->with('success', 'Trimester deleted successfully.');
    }
}
