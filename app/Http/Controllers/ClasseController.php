<?php

namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Classe;
use App\Models\NiveauPedagogique;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $classes = Classe::with(['niveau', 'specialite', 'annee'])->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $niveaux = NiveauPedagogique::all();
        $specialites = Speciality::all();
        $annees = Anneescolaire::all();
        return view('admin.classes.create', compact('niveaux', 'specialites', 'annees'));
    }




    public function store(Request $request)
    {
        $niveau = NiveauPedagogique::findOrFail($request->niveau_pedagogique_id);
        $speciality = Speciality::findOrFail($request->specialite_id);
        $nom = $speciality->code . '' . $niveau->ordre;

        $validator = Validator::make(array_merge($request->all(), ['nom' => $nom]), [
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
            'specialite_id' => 'required|exists:specialities,id',
            'annee_id' => 'required|exists:anneescolaires,id',
            'nom' => 'unique:classes,nom',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Classe::create([
            'nom' => $nom,
            'niveau_pedagogique_id' => $request->niveau_pedagogique_id,
            'specialite_id' => $request->specialite_id,
            'annee_id' => $request->annee_id,
        ]);

        return redirect()->route('web.classes.index')->with('success', 'Classe créée avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {

        $classe = Classe::findOrFail($id);
        if($classe->emplois()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer cette classe car elle est liée à des emplois du temps.');
        }
        $classe->delete();

        return redirect()->route('web.classes.index')->with('success', 'Classe supprimée avec succès.');
    }
}
