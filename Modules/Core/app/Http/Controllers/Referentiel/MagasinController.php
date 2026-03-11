<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Models\Referentiel\Emplacement;
use Modules\Core\Models\Referentiel\Magasin;

class MagasinController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.magasins.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.magasins.store', only: ['store', 'storeEmplacement']),
            new Middleware('permission:cores.referentiel.magasins.update', only: ['update']),
            new Middleware('permission:cores.referentiel.magasins.destroy', only: ['destroy', 'destroyEmplacement']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.magasins.index');
    }

    public function getData(Request $request)
    {
        $query = Magasin::query()->withCount('emplacements')->with('responsable');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $magasins = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $magasins,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_magasins,code',
            'libelle' => 'required',
            'type' => 'required|in:central,annexe,pharmacie',
        ]);

        try {
            $magasin = Magasin::create($request->all());

            return response()->json(['success' => true, 'message' => 'Magasin créé avec succès', 'data' => $magasin]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $magasin = Magasin::with(['emplacements', 'responsable'])->findOrFail($id);

            return response()->json(['success' => true, 'data' => $magasin]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Magasin non trouvé'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_magasins,code,'.$id,
            'libelle' => 'required',
            'type' => 'required|in:central,annexe,pharmacie',
        ]);

        try {
            $magasin = Magasin::findOrFail($id);
            $magasin->update($request->all());

            return response()->json(['success' => true, 'message' => 'Magasin modifié avec succès', 'data' => $magasin]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $magasin = Magasin::findOrFail($id);
            $magasin->delete();

            return response()->json(['success' => true, 'message' => 'Magasin supprimé (désactivé) avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    // --- Emplacement Methods ---

    public function storeEmplacement(Request $request)
    {
        $request->validate([
            'magasin_id' => 'required|exists:referentiel_magasins,id',
            'code' => 'required',
            'libelle' => 'required',
        ]);

        // Unique check (magasin_id + code)
        if (Emplacement::where('magasin_id', $request->magasin_id)->where('code', $request->code)->exists()) {
            return response()->json(['success' => false, 'message' => 'Ce code d\'emplacement existe déjà pour ce magasin.'], 422);
        }

        try {
            $emplacement = Emplacement::create($request->all());

            return response()->json(['success' => true, 'message' => 'Emplacement créé avec succès', 'data' => $emplacement]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroyEmplacement($id)
    {
        try {
            $emplacement = Emplacement::findOrFail($id);
            $emplacement->delete();

            return response()->json(['success' => true, 'message' => 'Emplacement supprimé (désactivé) avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }
    /**
     * Toggle status (actif/inactif).
     */
    public function toggleStatus($id)
    {
        try {
            $item = Magasin::findOrFail($id);
            $item->actif = !$item->actif;
            $item->save();

            return response()->json([
                'success' => true,
                'message' => $item->actif ? 'Élément activé avec succès' : 'Élément désactivé avec succès',
                'actif' => $item->actif,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut',
            ], 500);
        }
    }
}
