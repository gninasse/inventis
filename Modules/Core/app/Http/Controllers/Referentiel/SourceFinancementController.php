<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Models\Referentiel\SourceFinancement;

class SourceFinancementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.sources.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.sources.store', only: ['store']),
            new Middleware('permission:cores.referentiel.sources.update', only: ['update']),
            new Middleware('permission:cores.referentiel.sources.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.sources.index');
    }

    public function getData(Request $request)
    {
        $query = SourceFinancement::query();

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
        $sources = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $sources,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_sources_financement,code',
            'libelle' => 'required',
            'type' => 'required',
        ]);

        try {
            $source = SourceFinancement::create($request->all());

            return response()->json(['success' => true, 'message' => 'Source créée avec succès', 'data' => $source]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $source = SourceFinancement::findOrFail($id);

            return response()->json(['success' => true, 'data' => $source]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Source non trouvée'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_sources_financement,code,'.$id,
            'libelle' => 'required',
            'type' => 'required',
        ]);

        try {
            $source = SourceFinancement::findOrFail($id);
            $source->update($request->all());

            return response()->json(['success' => true, 'message' => 'Source modifiée avec succès', 'data' => $source]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $source = SourceFinancement::findOrFail($id);
            $source->delete();

            return response()->json(['success' => true, 'message' => 'Source supprimée (désactivée) avec succès']);
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
            $item = SourceFinancement::findOrFail($id);
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
