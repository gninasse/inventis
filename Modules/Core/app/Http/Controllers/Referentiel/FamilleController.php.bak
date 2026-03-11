<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Core\Http\Requests\Referentiel\StoreFamilleRequest;
use Modules\Core\Http\Requests\Referentiel\UpdateFamilleRequest;
use Modules\Core\Models\Referentiel\Categorie;
use Modules\Core\Models\Referentiel\Famille;
use Modules\Core\Models\Referentiel\SousCategorie;

class FamilleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::where('actif', true)->orderBy('libelle')->get();

        return view('core::referentiel.familles.index', compact('categories'));
    }

    /**
     * Get data for Bootstrap Table.
     */
    public function getData(Request $request)
    {
        $query = Famille::with('sousCategorie.categorie');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('libelle', 'like', "%{$search}%")
                    ->orWhereHas('sousCategorie', function ($q) use ($search) {
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
        $familles = $query->offset($offset)->limit($limit)->get();

        // Format data for table
        $rows = $familles->map(function ($f) {
            return [
                'id' => $f->id,
                'code' => $f->code,
                'libelle' => $f->libelle,
                'sous_categorie_id' => $f->sous_categorie_id,
                'sous_categorie_libelle' => $f->sousCategorie->libelle ?? '-',
                'categorie_id' => $f->sousCategorie->categorie_id ?? null,
                'categorie_libelle' => $f->sousCategorie->categorie->libelle ?? '-',
                'duree_amortissement' => $f->duree_amortissement,
                'actif' => $f->actif,
                'created_at' => $f->created_at,
            ];
        });

        return response()->json([
            'total' => $total,
            'rows' => $rows,
        ]);
    }

    /**
     * Get subcategories by category (for cascading dropdown).
     */
    public function getSousCategories($categorieId)
    {
        $sousCategories = SousCategorie::where('categorie_id', $categorieId)
            ->where('actif', true)
            ->orderBy('libelle')
            ->get(['id', 'code', 'libelle']);

        return response()->json([
            'success' => true,
            'data' => $sousCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFamilleRequest $request)
    {
        try {
            $famille = Famille::create([
                'sous_categorie_id' => $request->sous_categorie_id,
                'code' => $request->code,
                'libelle' => $request->libelle,
                'duree_amortissement' => $request->duree_amortissement,
                'actif' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Famille créée avec succès',
                'data' => $famille,
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
            $famille = Famille::with('sousCategorie.categorie')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $famille,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Famille non trouvée',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFamilleRequest $request, $id)
    {
        try {
            $famille = Famille::findOrFail($id);

            $famille->sous_categorie_id = $request->sous_categorie_id;
            $famille->code = $request->code;
            $famille->libelle = $request->libelle;
            $famille->duree_amortissement = $request->duree_amortissement;
            $famille->save();

            return response()->json([
                'success' => true,
                'message' => 'Famille modifiée avec succès',
                'data' => $famille,
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
            $famille = Famille::with('articles')->findOrFail($id);

            // Check for active articles
            if ($famille->articles()->where('actif', true)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer: des articles actifs sont liés à cette famille.',
                ], 403);
            }

            $famille->actif = false;
            $famille->save();

            return response()->json([
                'success' => true,
                'message' => 'Famille supprimée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression : '.$e->getMessage(),
            ], 500);
        }
    }
}
