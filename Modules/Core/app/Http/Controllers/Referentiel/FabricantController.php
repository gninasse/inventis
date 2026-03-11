<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Models\Referentiel\Fabricant;
use Modules\Core\Models\Referentiel\Marque;
use Modules\Core\Models\Referentiel\Modele;

class FabricantController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.fabricants.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.fabricants.store', only: ['store', 'storeMarque', 'storeModele']),
            new Middleware('permission:cores.referentiel.fabricants.update', only: ['update']),
            new Middleware('permission:cores.referentiel.fabricants.destroy', only: ['destroy', 'destroyMarque', 'destroyModele']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.fabricants.index');
    }

    public function getData(Request $request)
    {
        $query = Fabricant::query()->withCount(['marques']);

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('raison_sociale', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $fabricants = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $fabricants,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'raison_sociale' => 'required|unique:referentiel_fabricants,raison_sociale',
        ]);

        try {
            $fabricant = Fabricant::create($request->all());

            return response()->json(['success' => true, 'message' => 'Fabricant créé avec succès', 'data' => $fabricant]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $fabricant = Fabricant::with(['marques.modeles'])->findOrFail($id);

            return response()->json(['success' => true, 'data' => $fabricant]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Fabricant non trouvé'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'raison_sociale' => 'required|unique:referentiel_fabricants,raison_sociale,'.$id,
        ]);

        try {
            $fabricant = Fabricant::findOrFail($id);
            $fabricant->update($request->all());

            return response()->json(['success' => true, 'message' => 'Fabricant modifié avec succès', 'data' => $fabricant]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $fabricant = Fabricant::findOrFail($id);
            $fabricant->delete();

            return response()->json(['success' => true, 'message' => 'Fabricant supprimé (désactivé) avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    // --- Marques Methods ---

    public function storeMarque(Request $request)
    {
        $request->validate([
            'fabricant_id' => 'required|exists:referentiel_fabricants,id',
            'nom' => 'required',
        ]);

        // Unique check (fabricant_id + nom)
        if (Marque::where('fabricant_id', $request->fabricant_id)->where('nom', $request->nom)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cette marque existe déjà pour ce fabricant.'], 422);
        }

        try {
            $marque = Marque::create($request->all());

            return response()->json(['success' => true, 'message' => 'Marque créée avec succès', 'data' => $marque]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroyMarque($id)
    {
        try {
            $marque = Marque::findOrFail($id);
            $marque->delete();

            return response()->json(['success' => true, 'message' => 'Marque supprimée (désactivée) avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    // --- Modeles Methods ---

    public function storeModele(Request $request)
    {
        $request->validate([
            'marque_id' => 'required|exists:referentiel_marques,id',
            'reference' => 'required',
            'designation' => 'required',
        ]);

        // Unique check (marque_id + reference)
        if (Modele::where('marque_id', $request->marque_id)->where('reference', $request->reference)->exists()) {
            return response()->json(['success' => false, 'message' => 'Cette référence existe déjà pour cette marque.'], 422);
        }

        try {
            $modele = Modele::create($request->all());

            return response()->json(['success' => true, 'message' => 'Modèle créé avec succès', 'data' => $modele]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroyModele($id)
    {
        try {
            $modele = Modele::findOrFail($id);
            $modele->delete();

            return response()->json(['success' => true, 'message' => 'Modèle supprimé (désactivé) avec succès']);
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
            $item = Fabricant::findOrFail($id);
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
