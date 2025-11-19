<?php

namespace App\Http\Controllers;

use App\Models\Niveauformation;
use App\Models\SalleDeClasse;
use Illuminate\Http\Request;

class SalleDeClasseController extends Controller
{

    public function index()
    {
        return view('admin.salle_de_classes.index');
    }

    public function list(Request $request)
    {
        $query = SalleDeClasse::with('formation');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $total = $query->count();

        $rows = $query
            ->skip($request->offset ?? 0)
            ->take($request->limit ?? 10)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->get();

        return response()->json([
            'total' => $total,
            'rows' => $rows->map(function ($salle, $index) {
                $operate = '';
                $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $salle->id . '"><i class="fa fa-edit"></i></a> ';
                $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $salle->id . '" data-url="' . route('web.salle-de-classes.destroy', $salle->id) . '"><i class="fa fa-trash"></i></a>';

                return [
                    'id' => $salle->id,
                    'no' => $index + 1,
                    'nom' => $salle->name,
                    'code' => $salle->code,
                    'capacity' => $salle->capacity,
                    'formation' => $salle->formation->nom ?? '',
                    'operate' =>  $operate
                ];
            }),
        ]);
    }

    public function create()
    {
        $formations = Niveauformation::all();
        return view('admin.salle_de_classes.create', compact('formations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'formation_id' => 'nullable|exists:niveauformations,id',
            'code' => 'required|string|max:255',
        ]);

        SalleDeClasse::create($request->all());

        return redirect()->route('web.salle-de-classes.index')->with('success', 'Salle créée avec succès.');
    }

    public function edit(SalleDeClasse $salle_de_class)
    {
        $formations = Niveauformation::all();
        return view('admin.salle_de_classes.edit', compact('salle_de_class', 'formations'));
    }

    public function update(Request $request, SalleDeClasse $salle_de_class)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'formation_id' => 'nullable',
            'code' => 'required|string|max:255',
        ]);


        $salle_de_class->update($request->all());

        return redirect()->route('web.salle-de-classes.index')->with('success', 'Salle mise à jour avec succès.');
    }

    public function destroy(SalleDeClasse $salle_de_class)
    {
        $salle_de_class->delete();

        return response()->json(['message' => 'Salle supprimée avec succès.']);
    }
}
