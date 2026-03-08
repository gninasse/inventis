@extends('core::layouts.master')

@section('header', 'Gestion des catégories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item"><a href="#">Référentiel</a></li>
    <li class="breadcrumb-item active" aria-current="page">Catégories</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des catégories</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            <button id="btn-add-categorie" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter une catégorie">
                <i class="fas fa-plus"></i>
            </button>
            <button id="btn-edit-categorie" class="btn btn-info" disabled data-bs-toggle="tooltip" title="Modifier">
                <i class="fas fa-edit"></i>
            </button>
            <button id="btn-delete-categorie" class="btn btn-danger" disabled data-bs-toggle="tooltip" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <table id="categories-table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.categories.data') }}"
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
                    <th data-field="id" data-sortable="true">ID</th>
                    <th data-field="code" data-sortable="true">Code</th>
                    <th data-field="libelle" data-sortable="true">Libellé</th>
                    <th data-field="description" data-sortable="true">Description</th>
                    <th data-field="actif" data-sortable="true" data-formatter="actifFormatter">Actif</th>
                    <th data-field="created_at" data-sortable="true" data-formatter="dateFormatter">Date création</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.categories._modal')

@stop

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script type="module" src="{{ asset('js/modules/core/referentiel/categories/index.js') }}"></script>
@endpush
