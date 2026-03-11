<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Core\Http\Requests\Referentiel\StoreArticleRequest;
use Modules\Core\Http\Requests\Referentiel\UpdateArticleRequest;
use Modules\Core\Models\Referentiel\Article;
use Modules\Core\Models\Referentiel\Categorie;
use Modules\Core\Models\Referentiel\Famille;
use Modules\Core\Models\Referentiel\SousCategorie;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.articles.index', only: ['index', 'getData', 'show', 'getCategories', 'getSousCategories', 'getFamilles']),
            new Middleware('permission:cores.referentiel.articles.store', only: ['store']),
            new Middleware('permission:cores.referentiel.articles.update', only: ['update']),
            new Middleware('permission:cores.referentiel.articles.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.articles.index');
    }

    public function getData(Request $request)
    {
        $query = Article::query()->actif()->with('famille.sousCategorie.categorie');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'like', "%{$search}%")
                    ->orWhere('code_national', 'like', "%{$search}%");
            });
        }

        if ($request->has('famille_id') && ! empty($request->famille_id)) {
            $query->where('famille_id', $request->famille_id);
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $articles = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $articles,
        ]);
    }

    public function store(StoreArticleRequest $request)
    {
        try {
            $article = Article::create($request->validated());

            return response()->json(['success' => true, 'message' => 'Article créé avec succès', 'data' => $article]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $article = Article::with('famille.sousCategorie')->findOrFail($id);
            $data = $article->toArray();
            if ($article->famille && $article->famille->sousCategorie) {
                $data['categorie_id'] = $article->famille->sousCategorie->categorie_id;
                $data['sous_categorie_id'] = $article->famille->sous_categorie_id;
            }

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Article non trouvé'], 404);
        }
    }

    public function update(UpdateArticleRequest $request, $id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->update($request->validated());

            return response()->json(['success' => true, 'message' => 'Article modifié avec succès', 'data' => $article]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            $article->delete();

            return response()->json(['success' => true, 'message' => 'Article supprimé (désactivé) avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function getCategories()
    {
        $categories = Categorie::where('actif', true)->get();

        return response()->json($categories);
    }

    public function getSousCategories($categorieId)
    {
        $sousCategories = SousCategorie::where('categorie_id', $categorieId)->where('actif', true)->get();

        return response()->json($sousCategories);
    }

    public function getFamilles($sousCategorieId)
    {
        $familles = Famille::where('sous_categorie_id', $sousCategorieId)->where('actif', true)->get();

        return response()->json($familles);
    }
    /**
     * Toggle status (actif/inactif).
     */
    public function toggleStatus($id)
    {
        try {
            $item = Article::findOrFail($id);
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
