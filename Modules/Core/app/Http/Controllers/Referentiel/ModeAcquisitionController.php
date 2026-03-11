<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Models\Referentiel\ModeAcquisition;
use Modules\Core\Models\Referentiel\TypePiece;

class ModeAcquisitionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.modes-acquisition.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.modes-acquisition.store', only: ['store']),
            new Middleware('permission:cores.referentiel.modes-acquisition.update', only: ['update']),
            new Middleware('permission:cores.referentiel.modes-acquisition.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        // Pass TypePieces for the view (checkboxes)
        $typePieces = TypePiece::actif()->get();

        return view('core::referentiel.modes-acquisition.index', compact('typePieces'));
    }

    public function getData(Request $request)
    {
        $query = ModeAcquisition::query();

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
        $modes = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $modes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_modes_acquisition,code',
            'libelle' => 'required',
            'pieces_requises' => 'nullable|array',
        ]);

        try {
            $mode = ModeAcquisition::create($request->all());

            return response()->json(['success' => true, 'message' => 'Mode créé avec succès', 'data' => $mode]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $mode = ModeAcquisition::findOrFail($id);

            return response()->json(['success' => true, 'data' => $mode]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Mode non trouvé'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_modes_acquisition,code,'.$id,
            'libelle' => 'required',
            'pieces_requises' => 'nullable|array',
        ]);

        try {
            $mode = ModeAcquisition::findOrFail($id);
            $mode->update($request->all());

            return response()->json(['success' => true, 'message' => 'Mode modifié avec succès', 'data' => $mode]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mode = ModeAcquisition::findOrFail($id);
            $mode->delete();

            return response()->json(['success' => true, 'message' => 'Mode supprimé (désactivé) avec succès']);
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
            $item = ModeAcquisition::findOrFail($id);
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
