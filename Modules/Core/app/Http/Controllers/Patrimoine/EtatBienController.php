<?php

namespace Modules\Core\Http\Controllers\Patrimoine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Modules\Core\Models\Patrimoine\EtatBien;

class EtatBienController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:cores.patrimoine.etats.index', only: ['index', 'getData', 'show']),
            new Middleware('permission:cores.patrimoine.etats.store', only: ['store']),
            new Middleware('permission:cores.patrimoine.etats.update', only: ['update', 'reorder']),
            new Middleware('permission:cores.patrimoine.etats.destroy', only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('core::patrimoine.etats.index');
    }

    public function getData(Request $request)
    {
        $query = EtatBien::query()->orderBy('ordre', 'asc');

        return datatables()->of($query)
            ->addColumn('badge', function ($row) {
                return $row->badge_html;
            })
            ->addColumn('reforme_html', function ($row) {
                return $row->declencheur_reforme
                    ? '<span class="badge bg-danger">Oui</span>'
                    : '<span class="badge bg-secondary">Non</span>';
            })
            ->addColumn('action', function ($row) {
                $btns = '';
                if (auth()->user()->can('cores.patrimoine.etats.update')) {
                    $btns .= '<button class="btn btn-sm btn-info edit-etat me-1" data-id="'.$row->id.'"><i class="fas fa-edit"></i></button>';
                }
                if (auth()->user()->can('cores.patrimoine.etats.destroy') && ! $row->is_system) {
                    $btns .= '<button class="btn btn-sm btn-danger delete-etat" data-id="'.$row->id.'"><i class="fas fa-trash"></i></button>';
                }

                return $btns;
            })
            ->rawColumns(['badge', 'reforme_html', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:patrimoine_etats_bien,code',
        ]);

        try {
            $data = $request->except(['id']);
            $data['code'] = Str::upper(Str::slug($request->code, '_'));
            $data['actif'] = $request->has('actif') ? 1 : 0;
            $data['declencheur_reforme'] = $request->has('declencheur_reforme') ? 1 : 0;
            $data['is_system'] = 0;

            $maxOrdre = EtatBien::max('ordre');
            $data['ordre'] = $maxOrdre ? $maxOrdre + 10 : 10;

            EtatBien::create($data);

            return response()->json(['success' => true, 'message' => 'État créé avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $etat = EtatBien::findOrFail($id);

        return response()->json(['success' => true, 'data' => $etat]);
    }

    public function update(Request $request, $id)
    {
        $etat = EtatBien::findOrFail($id);

        $rules = [
            'libelle' => 'required|string|max:255',
        ];

        if (! $etat->is_system) {
            $rules['code'] = 'required|string|max:50|unique:patrimoine_etats_bien,code,'.$id;
        }

        $request->validate($rules);

        try {
            $data = $request->except(['id', '_token', '_method']);

            if (! $etat->is_system) {
                $data['code'] = Str::upper(Str::slug($request->code, '_'));
            } else {
                unset($data['code']);
            }

            $data['actif'] = $request->has('actif') ? 1 : 0;
            $data['declencheur_reforme'] = $request->has('declencheur_reforme') ? 1 : 0;

            $etat->update($data);

            return response()->json(['success' => true, 'message' => 'État mis à jour avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur: '.$e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $etat = EtatBien::findOrFail($id);
        if ($etat->is_system) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer un état système.'], 403);
        }

        $etat->delete();

        return response()->json(['success' => true, 'message' => 'État supprimé avec succès.']);
    }

    public function reorder(Request $request)
    {
        $items = $request->input('items', []);
        foreach ($items as $item) {
            EtatBien::where('id', $item['id'])->update(['ordre' => $item['ordre']]);
        }

        return response()->json(['success' => true]);
    }
}
