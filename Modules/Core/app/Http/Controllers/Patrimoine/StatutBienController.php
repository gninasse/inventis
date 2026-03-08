<?php

namespace Modules\Core\Http\Controllers\Patrimoine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Modules\Core\Models\Patrimoine\StatutBien;

class StatutBienController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.patrimoine.statuts.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.patrimoine.statuts.store', only: ['store']),
            new Middleware('permission:cores.patrimoine.statuts.update', only: ['update', 'reorder']),
            new Middleware('permission:cores.patrimoine.statuts.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::patrimoine.statuts.index');
    }

    public function getData(Request $request)
    {
        $query = StatutBien::query()->orderBy('ordre', 'asc');

        return datatables()->of($query)
            ->addColumn('badge', function ($row) {
                return $row->badge_html;
            })
            ->addColumn('impact_html', function ($row) {
                return $row->impact_comptable
                    ? '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> Oui</span>'
                    : '<span class="badge bg-secondary">Non</span>';
            })
            ->addColumn('action', function ($row) {
                $btns = '';
                if (auth()->user()->can('cores.patrimoine.statuts.update')) {
                    $btns .= '<button class="btn btn-sm btn-info edit-statut me-1" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button>';
                }
                if (auth()->user()->can('cores.patrimoine.statuts.destroy') && ! $row->is_system) {
                    $btns .= '<button class="btn btn-sm btn-danger delete-statut" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>';
                }

                return $btns;
            })
            ->rawColumns(['badge', 'impact_html', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:patrimoine_statuts_bien,code',
        ]);

        try {
            $data = $request->except(['id']); // Clean input
            $data['code'] = Str::upper(Str::slug($request->code, '_'));
            $data['actif'] = $request->has('actif') ? 1 : 0;
            $data['impact_comptable'] = $request->has('impact_comptable') ? 1 : 0;
            $data['is_system'] = 0;

            // Auto order
            $maxOrdre = StatutBien::max('ordre');
            $data['ordre'] = $maxOrdre ? $maxOrdre + 10 : 10;

            StatutBien::create($data);

            return response()->json(['success' => true, 'message' => 'Statut crée avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $statut = StatutBien::findOrFail($id);

        return response()->json(['success' => true, 'data' => $statut]);
    }

    public function update(Request $request, $id)
    {
        $statut = StatutBien::findOrFail($id);

        $rules = [
            'libelle' => 'required|string|max:255',
        ];

        if (! $statut->is_system) {
            $rules['code'] = 'required|string|max:50|unique:patrimoine_statuts_bien,code,'.$id;
        }

        $request->validate($rules);

        try {
            $data = $request->except(['id', '_token', '_method']);

            if (! $statut->is_system) {
                $data['code'] = Str::upper(Str::slug($request->code, '_'));
            } else {
                unset($data['code']);
            }

            $data['actif'] = $request->has('actif') ? 1 : 0;
            $data['impact_comptable'] = $request->has('impact_comptable') ? 1 : 0;

            $statut->update($data);

            return response()->json(['success' => true, 'message' => 'Statut mis à jour avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $statut = StatutBien::findOrFail($id);
        if ($statut->is_system) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer un statut système.'], 403);
        }

        $statut->delete();

        return response()->json(['success' => true, 'message' => 'Statut supprimé avec succès.']);
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items', []);
        foreach ($items as $item) {
            StatutBien::where('id', $item['id'])->update(['ordre' => $item['ordre']]);
        }

        return response()->json(['success' => true]);
    }
}
