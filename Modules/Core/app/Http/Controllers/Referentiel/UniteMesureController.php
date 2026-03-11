<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Http\Requests\StoreUniteMesureRequest;
use Modules\Core\Http\Requests\UpdateUniteMesureRequest;
use Modules\Core\Models\Referentiel\UniteMesure;

class UniteMesureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.unites.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.unites.store', only: ['store']),
            new Middleware('permission:cores.referentiel.unites.update', only: ['update']),
            new Middleware('permission:cores.referentiel.unites.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.unites.index');
    }

    public function getData(Request $request)
    {
        $query = UniteMesure::query();

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                    ->orWhere('abreviation', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $unites = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $unites,
        ]);
    }

    public function store(StoreUniteMesureRequest $request)
    {
        try {
            $unite = UniteMesure::create($request->validated());

            return response()->json(['success' => true, 'message' => 'Unité créée avec succès', 'data' => $unite]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $unite = UniteMesure::findOrFail($id);

            return response()->json(['success' => true, 'data' => $unite]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unité non trouvée'], 404);
        }
    }

    public function update(UpdateUniteMesureRequest $request, $id)
    {
        try {
            $unite = UniteMesure::findOrFail($id);
            $unite->update($request->validated());

            return response()->json(['success' => true, 'message' => 'Unité modifiée avec succès', 'data' => $unite]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $unite = UniteMesure::findOrFail($id);
            $unite->delete();

            return response()->json(['success' => true, 'message' => 'Unité supprimée (désactivée) avec succès']);
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
            $item = UniteMesure::findOrFail($id);
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
