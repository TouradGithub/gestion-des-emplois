<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.departements.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departements,code',
        ]);

        $departement = Departement::create($validated);

        return redirect()->route('web.departements.index')
            ->with('success', 'Département créé avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function list(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';

        $query = Departement::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%");
            });
        }

        $total = $query->count();
        $departements = $query->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($departements as $departement) {
            $operate = '';
//        if (auth()->user()->can('departements-edit')) {
            $operate .= '<a class="btn btn-xs btn-gradient-primary editdata" data-id="' . $departement->id . '"><i class="fa fa-edit"></i></a> ';
//        }
//        if (auth()->user()->can('departements-delete')) {
            $operate .= '<a class="btn btn-xs btn-gradient-danger deletedata" data-id="' . $departement->id . '" data-url="' . route('web.departements.destroy', $departement->id) . '"><i class="fa fa-trash"></i></a>';
//        }

            $rows[] = [
                'id' => $departement->id,
                'no' => $no++,
                'name' => $departement->name,
                'code' => $departement->code,
                'operate' => $operate,
            ];
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $departement)
    {
        try {
            $d =   Departement::findOrFail($departement);
            $d->delete();

            return response()->json([
                'success' => true,
                'message' => 'Département supprimé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du département.',
            ], 500);
        }
    }

}
