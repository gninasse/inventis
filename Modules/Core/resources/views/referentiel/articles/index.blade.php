@extends('core::layouts.master')

@section('header', 'Gestion des Articles')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Articles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des articles</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.articles.store')
            <button id="btn-add-article" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter un article">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
            @can('cores.referentiel.articles.update')
            <button id="btn-edit-article" class="btn btn-info" disabled data-bs-toggle="tooltip" title="Modifier">
                <i class="fas fa-edit"></i>
            </button>
            @endcan
            @can('cores.referentiel.articles.destroy')
            <button id="btn-delete-article" class="btn btn-danger" disabled data-bs-toggle="tooltip" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
            @endcan
        </div>
        <table id="articles-table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.articles.data') }}"
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
                    <th data-field="code_national" data-sortable="true">Code National</th>
                    <th data-field="designation" data-sortable="true">Désignation</th>
                    <th data-field="type" data-sortable="true" data-formatter="typeFormatter">Type</th>
                    <th data-field="famille_path" data-sortable="false">Chemin Famille</th>
                    <th data-field="created_at" data-sortable="true" data-formatter="dateFormatter">Date création</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.articles._modal')

@stop

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script type="module" src="{{ asset('js/modules/core/referentiel/articles/index.js') }}"></script>
@endpush
