<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Models\Referentiel\Fournisseur;

class FournisseurController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.fournisseurs.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.fournisseurs.store', only: ['store']),
            new Middleware('permission:cores.referentiel.fournisseurs.update', only: ['update']),
            new Middleware('permission:cores.referentiel.fournisseurs.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.fournisseurs.index');
    }

    public function getData(Request $request)
    {
        $query = Fournisseur::query();

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('raison_sociale', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $fournisseurs = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $fournisseurs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'raison_sociale' => 'required',
            'type' => 'required|in:commercial,donateur,institution',
            'pays' => 'required',
            'ifu' => 'nullable|string',
            // Unique check on 'raison_sociale' soft unique requested
            'email' => 'nullable|email',
        ]);

        // Custom unique check for IFU if present
        if ($request->filled('ifu')) {
            if (Fournisseur::where('ifu', $request->ifu)->exists()) {
                return response()->json(['success' => false, 'message' => 'Cet IFU existe déjà.'], 422);
            }
        }

        try {
            $fournisseur = Fournisseur::create($request->all());

            return response()->json(['success' => true, 'message' => 'Fournisseur créé avec succès', 'data' => $fournisseur]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $fournisseur = Fournisseur::findOrFail($id);

            return response()->json(['success' => true, 'data' => $fournisseur]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Fournisseur non trouvé'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'raison_sociale' => 'required',
            'type' => 'required|in:commercial,donateur,institution',
            'pays' => 'required',
            'ifu' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if ($request->filled('ifu')) {
            if (Fournisseur::where('ifu', $request->ifu)->where('id', '!=', $id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Cet IFU existe déjà.'], 422);
            }
        }

        try {
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->update($request->all());

            return response()->json(['success' => true, 'message' => 'Fournisseur modifié avec succès', 'data' => $fournisseur]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->actif = false;
            $fournisseur->save();

            return response()->json(['success' => true, 'message' => 'Fournisseur supprimé (désactivé) avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }
}
