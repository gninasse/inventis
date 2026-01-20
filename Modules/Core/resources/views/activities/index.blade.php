@extends('core::layouts.master')

@section('header', 'Journal des activités')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Journal des activités</li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Activités</h6>
                        <h3 class="mt-2 mb-0">{{ \Modules\Core\Models\Activity::count() }}</h3>
                    </div>
                    <i class="fas fa-list fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Aujourd'hui</h6>
                        <h3 class="mt-2 mb-0">{{ \Modules\Core\Models\Activity::whereDate('created_at', today())->count() }}</h3>
                    </div>
                    <i class="fas fa-calendar-day fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Filtres
        </h5>
    </div>
    <div class="card-body">
        <form id="filter-form" class="row g-3">
            <div class="col-md-3">
                <label for="module" class="form-label">Module</label>
                <select name="module" id="module" class="form-select">
                    <option value="">Tous les modules</option>
                    @foreach($modules as $slug => $name)
                        <option value="{{ $slug }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_id" class="form-label">Utilisateur</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="action_type" class="form-label">Action</label>
                <select name="action_type" id="action_type" class="form-select">
                    <option value="">Toutes les actions</option>
                    <option value="created">Création</option>
                    <option value="updated">Modification</option>
                    <option value="deleted">Suppression</option>
                    <option value="login">Connexion</option>
                    <option value="logout">Déconnexion</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Depuis le</label>
                <input type="date" name="date_from" id="date_from" class="form-control">
            </div>
            <div class="col-md-12 text-end">
                <button type="button" id="btn-reset" class="btn btn-secondary">Réinitialiser</button>
                <button type="button" id="btn-apply" class="btn btn-primary">Appliquer les filtres</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table id="activities-table"
               data-toggle="table"
               data-url="{{ route('cores.activities.data') }}"
               data-pagination="true"
               data-side-pagination="server"
               data-search="true"
               data-show-refresh="true"
               data-show-columns="true"
               data-sort-name="created_at"
               data-sort-order="desc"
               data-page-list="[10, 25, 50, 100]">
            <thead>
                <tr>
                    <th data-field="icon" data-formatter="actionFormatter" data-width="50">Type</th>
                    <th data-field="created_at" data-sortable="true">Date/Heure</th>
                    <th data-field="module" data-sortable="true">Module</th>
                    <th data-field="description" data-sortable="true" data-formatter="descriptionFormatter">Action</th>
                    <th data-field="causer_name" data-sortable="false">Utilisateur</th>
                    <th data-field="causer_roles" data-formatter="rolesFormatter">Rôles</th>
                    <th data-field="subject_type" data-sortable="true">Modèle</th>
                    <th data-field="id" data-formatter="operateFormatter" data-width="100">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modale de détails -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="width: 80%; margin-left: auto; margin-right: auto;">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-content">
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
<style>
    .modal-xl .modal-content {
        width: 80% !important;
        margin: 0 auto;
    }
</style>
@endpush

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script type="module" src="{{ asset('js/modules/core/activities/activity.js') }}"></script>
@endpush
