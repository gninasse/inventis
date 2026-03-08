@extends('core::layouts.master')

@section('header', 'Gestion des Fournisseurs')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Fournisseurs</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des fournisseurs</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.fournisseurs.store')
            <button id="btn-add" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
            @can('cores.referentiel.fournisseurs.update')
            <button id="btn-edit" class="btn btn-info" disabled data-bs-toggle="tooltip" title="Modifier">
                <i class="fas fa-edit"></i>
            </button>
            @endcan
            @can('cores.referentiel.fournisseurs.destroy')
            <button id="btn-delete" class="btn btn-danger" disabled data-bs-toggle="tooltip" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
            @endcan
        </div>
        <table id="table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.fournisseurs.data') }}"
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
                    <th data-field="raison_sociale" data-sortable="true">Raison Sociale</th>
                    <th data-field="type" data-sortable="true" data-formatter="typeFormatter">Type</th>
                    <th data-field="email" data-sortable="true">Email</th>
                    <th data-field="telephone" data-sortable="true">Téléphone</th>
                    <th data-field="pays" data-sortable="true">Pays</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.fournisseurs._modal')
@stop

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    function typeFormatter(value) {
        if (value === 'commercial') return '<span class="badge bg-info">Commercial</span>';
        if (value === 'donateur') return '<span class="badge bg-success">Donateur</span>';
        if (value === 'institution') return '<span class="badge bg-warning text-dark">Institution</span>';
        return value;
    }
</script>

<script type="module" src="{{ asset('js/modules/core/fournisseurs/index.js') }}"></script>
@endpush
