<?php

namespace App\Http\Controllers;

use App\Models\Niveauformation;
use App\Models\NiveauPedagogique;
use Illuminate\Http\Request;

class NiveauPedagogiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $niveaux = NiveauPedagogique::with('formation')->latest()->get();
        return view('admin.niveau-pedagogiques.index', compact('niveaux'));
    }

    public function create()
    {
        $formations = Niveauformation::all();
        return view('admin.niveau-pedagogiques.create', compact('formations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'ordre' => 'nullable|string|max:255',
            'formation_id' => 'nullable|exists:niveauformations,id',
        ]);

        NiveauPedagogique::create($request->all());
        return redirect()->route('web.niveau-pedagogiques.index')->with('success', 'Niveau créé avec succès.');
    }

    public function edit(NiveauPedagogique $niveauPedagogique)
    {
        $formations = Niveauformation::all();
        return view('admin.niveau-pedagogiques.edit', compact('niveauPedagogique', 'formations'));
    }

    public function update(Request $request, NiveauPedagogique $niveauPedagogique)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'ordre' => 'nullable|string|max:255',
            'formation_id' => 'nullable|exists:niveauformations,id',
        ]);

        $niveauPedagogique->update($request->all());
        return redirect()->route('web.niveau-pedagogiques.index')->with('success', 'Niveau modifié avec succès.');
    }

    public function destroy( $niveauPedagogique)
    {
        $n = NiveauPedagogique::findOrFail($niveauPedagogique);
        $n->delete();
        return response()->json(['success' => true]);
    }
}
