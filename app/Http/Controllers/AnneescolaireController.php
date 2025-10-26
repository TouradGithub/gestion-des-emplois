<?php

namespace App\Http\Controllers;

use App\Models\Anneescolaire;
use App\Models\Niveauformation;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AnneescolaireController extends Controller
{


    public function index()
    {
        return view('admin.anneescolaires.index');
    }

    public function list(Request $request)
    {
        $query = Anneescolaire::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('annee', 'like', '%' . $request->search . '%');
        }

        $total = $query->count();
        $rows = $query->offset($request->offset ?? 0)
            ->limit($request->limit ?? 10)
            ->orderBy($request->sort ?? 'id', $request->order ?? 'desc')
            ->get();

        $data = [];
        $index = $request->offset + 1;

        foreach ($rows as $row) {
            $data[] = [
                'id' => $row->id,
                'no' => $index++,
                'annee' => $row->annee,
                'date_debut' => $row->date_debut,
                'date_fin' => $row->date_fin,
                'is_active' => $row->is_active ? 'Oui' : 'Non',
                'operate' => view('components.action-buttons', [
                    'id' => $row->id,
                    'editRoute' => route('web.anneescolaires.edit', $row->id),
                    'deleteRoute' => route('web.anneescolaires.destroy', $row->id)
                ])->render()
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $data,
        ]);
    }

    public function create()
    {
        return view('admin.anneescolaires.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'annee' => 'required|string|unique:anneescolaires,annee',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        Anneescolaire::create($request->only('annee', 'date_debut', 'date_fin', 'is_active'));

        return redirect()->route('web.anneescolaires.index')->with('success', 'Année scolaire ajoutée avec succès');
    }

    public function edit(Anneescolaire $anneescolaire)
    {
        return view('admin.anneescolaires.edit', compact('anneescolaire'));
    }

    public function update(Request $request, Anneescolaire $anneescolaire)
    {

        $request->validate([
            'annee' => 'required|string|unique:anneescolaires,annee,' . $anneescolaire->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        $anneescolaire->update($request->only('annee', 'date_debut', 'date_fin', 'is_active'));

        return redirect()->route('web.anneescolaires.index')->with('success', 'Année scolaire mise à jour avec succès');
    }

    public function destroy(Anneescolaire $anneescolaire)
    {
        $anneescolaire->delete();
        return response()->json([
            'success' => true,
            'message' => 'Année scolaire supprimée avec succès'
        ]);
    }


    public function show(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Anneescolaire::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $anneescolaires = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($anneescolaires as $anneescolaire) {
            $operate = '';
//            if (auth()->user()->can('teachers-edit')) {
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $anneescolaire->id . '"><i class="fa fa-edit"></i></a> ';
//            }
//            if (auth()->user()->can('teachers-delete')) {
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $anneescolaire->id . '" data-url="' . route('web.anneescolaires.destroy', $anneescolaire->id) . '"><i class="fa fa-trash"></i></a>';
//            }
            $isActive = $anneescolaire->is_active ? '<div class="btn btn-xs btn-primary">Oui</div>' : '<div class="btn btn-xs btn-danger">Non</div>';
            $rows[] = [
                'id' => $anneescolaire->id,
                'no' => $no++,
                'annee' => $anneescolaire->annee,
                'date_debut' => $anneescolaire->date_debut,
                'date_fin' => $anneescolaire->date_fin,
                'is_active' =>$isActive,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

}
