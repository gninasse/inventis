<?php

namespace Modules\Core\Http\Controllers\Referentiel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Referentiel\Budget;

class BudgetController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.referentiel.budgets.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.referentiel.budgets.store', only: ['store']),
            new Middleware('permission:cores.referentiel.budgets.update', only: ['update']),
            new Middleware('permission:cores.referentiel.budgets.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::referentiel.budgets.index');
    }

    public function getData(Request $request)
    {
        $query = Budget::query()->with('responsable');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('exercice', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'id');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $total = $query->count();
        $budgets = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'total' => $total,
            'rows' => $budgets,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_budgets,code',
            'libelle' => 'required',
            'exercice' => 'required|integer|unique:referentiel_budgets,exercice',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'montant_initial' => 'required|numeric',
            'statut' => 'required|in:previsionnel,en_cours,cloture',
        ]);

        try {
            DB::beginTransaction();

            // Règle: un seul budget en_cours à la fois
            if ($request->statut === 'en_cours') {
                $previousBudget = Budget::getBudgetEnCours();
                if ($previousBudget) {
                    $previousBudget->statut = 'cloture';
                    $previousBudget->save();
                }
            }

            // Calculate disponible (initial - engage) = initial (since engage is 0 on creation)
            $request->merge(['montant_disponible' => $request->montant_initial]);

            $budget = Budget::create($request->all());

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Budget créé avec succès', 'data' => $budget]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $budget = Budget::with('responsable')->findOrFail($id);

            return response()->json(['success' => true, 'data' => $budget]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Budget non trouvé'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|unique:referentiel_budgets,code,'.$id,
            'libelle' => 'required',
            'exercice' => 'required|integer|unique:referentiel_budgets,exercice,'.$id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'montant_initial' => 'required|numeric',
            'statut' => 'required|in:previsionnel,en_cours,cloture',
        ]);

        try {
            DB::beginTransaction();

            $budget = Budget::findOrFail($id);

            // Règle: un seul budget en_cours à la fois
            if ($request->statut === 'en_cours' && $budget->statut !== 'en_cours') {
                $previousBudget = Budget::getBudgetEnCours();
                if ($previousBudget && $previousBudget->id !== $id) {
                    $previousBudget->statut = 'cloture';
                    $previousBudget->save();
                }
            }

            // Update calculable fields logic if amount changed (simplified here)
            // Available = Initial - Engaged
            $engaged = $budget->montant_engage;
            $request->merge(['montant_disponible' => $request->montant_initial - $engaged]);

            $budget->update($request->all());

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Budget modifié avec succès', 'data' => $budget]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $budget = Budget::findOrFail($id);
            $budget->delete();

            return response()->json(['success' => true, 'message' => 'Budget supprimé (désactivé) avec succès']);
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
            $item = Budget::findOrFail($id);
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
