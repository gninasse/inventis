<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Referentiel\StoreSousCategorieRequest;
use Modules\Core\Http\Requests\Referentiel\UpdateSousCategorieRequest;
use Modules\Core\Models\Referentiel\Categorie;
use Modules\Core\Models\Referentiel\SousCategorie;

class SousCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::where('actif', true)->orderBy('libelle')->get();

        return view('core::referentiel.sous-categories.index', compact('categories'));
    }

    /**
     * Get data for Bootstrap Table.
     */
    public function getData(Request $request)
    {
        $query = SousCategorie::with('categorie');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('libelle', 'like', "%{$search}%")
                    ->orWhereHas('categorie', function ($q) use ($search) {
                        $q->where('libelle', 'like', "%{$search}%");
                    });
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $sousCategories = $query->offset($offset)->limit($limit)->get();

        // Format data for table
        $rows = $sousCategories->map(function ($sc) {
            return [
                'id' => $sc->id,
                'code' => $sc->code,
                'libelle' => $sc->libelle,
                'categorie_id' => $sc->categorie_id,
                'categorie_libelle' => $sc->categorie->libelle ?? '-',
                'actif' => $sc->actif,
                'created_at' => $sc->created_at,
            ];
        });

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSousCategorieRequest $request)
    {
        try {
            $sousCategorie = SousCategorie::create([
                'categorie_id' => $request->categorie_id,
                'code' => $request->code,
                'libelle' => $request->libelle,
                'actif' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sous-catégorie créée avec succès',
                'data' => $sousCategorie,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création : '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $sousCategorie = SousCategorie::with('categorie')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $sousCategorie,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sous-catégorie non trouvée',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSousCategorieRequest $request, $id)
    {
        try {
            $sousCategorie = SousCategorie::findOrFail($id);

            $sousCategorie->categorie_id = $request->categorie_id;
            $sousCategorie->code = $request->code;
            $sousCategorie->libelle = $request->libelle;
            $sousCategorie->save();

            return response()->json([
                'success' => true,
                'message' => 'Sous-catégorie modifiée avec succès',
                'data' => $sousCategorie,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        try {
            $sousCategorie = SousCategorie::with('familles.articles')->findOrFail($id);

            // Check for active articles
            foreach ($sousCategorie->familles as $famille) {
                if ($famille->articles()->where('actif', true)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer: des articles actifs sont liés à cette sous-catégorie.',
                    ], 403);
                }
            }

            $sousCategorie->actif = false;
            $sousCategorie->save();

            return response()->json([
                'success' => true,
                'message' => 'Sous-catégorie supprimée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : '.$e->getMessage(),
            ], 500);
        }
    }
}
