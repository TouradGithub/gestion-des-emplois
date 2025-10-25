<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Niveauformation;
use App\Models\Speciality;
use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.specialities.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departements = Departement::all();
        $niveaux  = Niveauformation::all();
        return view('admin.specialities.create', compact('departements' , 'niveaux'));
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(Speciality $speciality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $speciality = Speciality::findOrFail($id);
        $departements = Departement::all();
        $niveaux = Niveauformation::all();
        return view('admin.specialities.edit', compact('speciality', 'departements', 'niveaux'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $speciality = Speciality::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:specialities,code,' . $speciality->id,
            'niveau_formation_id' => 'required|exists:niveauformations,id',
            'departement_id' => 'required|exists:departements,id',
        ]);

        $speciality->update($validated);

        return redirect()->route('web.specialities.index')
            ->with('success', 'Spécialité modifiée avec succès.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($speciality)
    {
        try {
            $s = Speciality::findOrFail($speciality);
            $s->delete();
            return response()->json(['message' => 'Spécialité supprimée avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de la suppression.'.$e->getMessage()], 500);
        }
    }


    public function list(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Speciality::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
        }

        $total = $query->count();

        $departements = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($departements as $speciality) {
            $operate = '';
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $speciality->id . '"><i class="fa fa-edit"></i></a> ';
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $speciality->id . '" data-url="' . route('web.specialities.destroy', $speciality->id) . '"><i class="fa fa-trash"></i></a>';

            $rows[] = [
                'id' => $speciality->id,
                'no' => $no++,
                'name' => $speciality->name,
                'code' => $speciality->code,
                'departement' => $speciality->departement->name ?? 'N/A',
                'niveau' => $speciality->niveau->nom ?? 'N/A',
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:specialities,code',
            'niveau_formation_id' => 'required|exists:niveauformations,id',
            'departement_id' => 'required|exists:departements,id',
        ]);

        Speciality::create($validated);

        return redirect()->route('web.specialities.index')
            ->with('success', 'Spécialité créée avec succès.');
    }


}
