@extends('core::layouts.master')

@section('header', 'Sources de Financement')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Sources de Financement</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des sources</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.sources.store')
            <button id="btn-add" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
            @can('cores.referentiel.sources.update')
            <button id="btn-edit" class="btn btn-info" disabled data-bs-toggle="tooltip" title="Modifier">
                <i class="fas fa-edit"></i>
            </button>
            @endcan
            @can('cores.referentiel.sources.destroy')
            <button id="btn-delete" class="btn btn-danger" disabled data-bs-toggle="tooltip" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
            @endcan
        </div>
        <table id="table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.sources.data') }}"
               data-pagination="true"
               data-side-pagination="server"
               data-search="true"
               data-show-refresh="true"
               data-show-columns="true"
               data-toolbar="#toolbar"
               data-click-to-select="true"
               data-single-select="true"
               data-id-field="id"
               data-page-list="[10, 25, 50, 100]">
            <thead>
                <tr>
                    <th data-field="state" data-radio="true"></th>
                    <th data-field="code" data-sortable="true">Code</th>
                    <th data-field="libelle" data-sortable="true">Libellé</th>
                    <th data-field="type" data-sortable="true">Type</th>
                    <th data-field="organisme" data-sortable="true">Organisme</th>
                    <th data-field="exercice_debut" data-sortable="true">Début</th>
                    <th data-field="exercice_fin" data-sortable="true">Fin</th>
                    <th data-field="montant_alloue" data-sortable="true" data-formatter="moneyFormatter">Montant</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.sources._modal')
@stop

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function moneyFormatter(value) {
        if (!value) return '-';
        return parseFloat(value).toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' });
    }
</script>

<script type="module" src="{{ asset('js/modules/core/sources/index.js') }}"></script>
@endpush
