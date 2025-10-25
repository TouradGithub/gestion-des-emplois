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
    public function edit($id)
    {
        $classe = Classe::findOrFail($id);
        $niveaux = NiveauPedagogique::all();
        $specialites = Speciality::all();
        $annees = Anneescolaire::all();
        return view('admin.classes.edit', compact('classe', 'niveaux', 'specialites', 'annees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $classe = Classe::findOrFail($id);

        $niveau = NiveauPedagogique::findOrFail($request->niveau_pedagogique_id);
        $speciality = Speciality::findOrFail($request->specialite_id);
        $nom = $speciality->code . '' . $niveau->ordre;

        $validator = Validator::make(array_merge($request->all(), ['nom' => $nom]), [
            'niveau_pedagogique_id' => 'required|exists:niveau_pedagogiques,id',
            'specialite_id' => 'required|exists:specialities,id',
            'annee_id' => 'required|exists:anneescolaires,id',
            'nom' => 'unique:classes,nom,' . $classe->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $classe->update([
            'nom' => $nom,
            'niveau_pedagogique_id' => $request->niveau_pedagogique_id,
            'specialite_id' => $request->specialite_id,
            'annee_id' => $request->annee_id,
        ]);

        return redirect()->route('web.classes.index')->with('success', 'Classe modifiée avec succès.');
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

    public function list(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Classe::with(['niveau', 'specialite', 'annee']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $classes = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($classes as $classe) {
            $operate = '';
            $operate .= '<a class="btn btn-xs btn-gradient-primary" href="' . route('web.classes.edit', $classe->id) . '"><i class="fa fa-edit"></i></a> ';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $classe->id . '" data-url="' . route('web.classes.destroy', $classe->id) . '"><i class="fa fa-trash"></i></a>';

            $rows[] = [
                'id' => $classe->id,
                'no' => $no++,
                'nom' => $classe->nom,
                'niveau' => $classe->niveau->nom ?? 'N/A',
                'specialite' => $classe->specialite->name ?? 'N/A',
                'annee' => $classe->annee->annee ?? 'N/A',
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }
}
