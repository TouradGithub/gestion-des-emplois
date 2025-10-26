<?php

namespace App\Http\Controllers;

use App\Models\Niveauformation;
use App\Models\Teacher;
use Illuminate\Http\Request;

class NiveauformationController extends Controller
{
    public function index()
    {

        return view('admin.niveauformations.index');
    }

    public function create()
    {
        return view('admin.niveauformations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'ordre' => 'required|string|max:255',
        ]);

        Niveauformation::create($request->all());

        return redirect()->route('web.niveauformations.index')->with('success', 'Ajouté avec succès.');
    }

    public function show(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Niveauformation::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $niveauformations = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($niveauformations as $niveauformation) {
            $operate = '';
//            if (auth()->user()->can('teachers-edit')) {
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $niveauformation->id . '"><i class="fa fa-edit"></i></a> ';
//            }
//            if (auth()->user()->can('teachers-delete')) {
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $niveauformation->id . '" data-url="' . route('web.niveauformations.destroy', $niveauformation->id) . '"><i class="fa fa-trash"></i></a>';
//            }

            $rows[] = [
                'id' => $niveauformation->id,
                'no' => $no++,
                'nom' => $niveauformation->nom,
                'ordre' => $niveauformation->ordre,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    public function edit(Niveauformation $niveauformation)
    {
        return view('admin.niveauformations.edit', compact('niveauformation'));
    }

    public function update(Request $request, Niveauformation $niveauformation)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $niveauformation->update($request->all());

        return redirect()->route('web.niveauformations.index')->with('success', 'Modifié avec succès.');
    }

    public function destroy(Niveauformation $niveauformation)
    {
        try {
            $niveauformation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supprimé avec succès.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ], 500);
        }
    }
}
