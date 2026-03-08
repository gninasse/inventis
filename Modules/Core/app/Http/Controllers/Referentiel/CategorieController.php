<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Referentiel\StoreCategorieRequest;
use Modules\Core\Http\Requests\Referentiel\UpdateCategorieRequest;
use Modules\Core\Models\Referentiel\Categorie;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core::referentiel.categories.index');
    }

    /**
     * Get data for Bootstrap Table.
     */
    public function getData(Request $request)
    {
        $query = Categorie::query();

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('libelle', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $categories = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategorieRequest $request)
    {
        try {
            $categorie = Categorie::create([
                'code' => $request->code,
                'libelle' => $request->libelle,
                'description' => $request->description,
                'actif' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'data' => $categorie,
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
            $categorie = Categorie::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $categorie,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Catégorie non trouvée',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorieRequest $request, $id)
    {
        try {
            $categorie = Categorie::findOrFail($id);

            $categorie->code = $request->code;
            $categorie->libelle = $request->libelle;
            $categorie->description = $request->description;
            $categorie->save();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie modifiée avec succès',
                'data' => $categorie,
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
            $categorie = Categorie::with('sousCategories.familles.articles')->findOrFail($id);

            // Check for active articles
            foreach ($categorie->sousCategories as $sousCategorie) {
                foreach ($sousCategorie->familles as $famille) {
                    if ($famille->articles()->where('actif', true)->exists()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Impossible de supprimer: des articles actifs sont liés à cette catégorie.',
                        ], 403);
                    }
                }
            }

            $categorie->actif = false;
            $categorie->save();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : '.$e->getMessage(),
            ], 500);
        }
    }
}
