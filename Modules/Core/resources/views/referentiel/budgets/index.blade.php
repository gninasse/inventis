@extends('core::layouts.master')

@section('header', 'Gestion des Budgets')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Budgets</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des budgets</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.budgets.store')
            <button id="btn-add" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter">
                <i class="fas fa-plus"></i> Nouveau
            </button>
            @endcan
        </div>
        <table id="table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.budgets.data') }}"
               data-pagination="true"
               data-side-pagination="server"
               data-search="true"
               data-show-refresh="true"
               data-toolbar="#toolbar"
               data-id-field="id">
            <thead>
                <tr>
                    <th data-field="code" data-sortable="true">Code</th>
                    <th data-field="libelle" data-sortable="true">Libellé</th>
                    <th data-field="exercice" data-sortable="true">Exercice</th>
                    <th data-field="date_debut" data-sortable="true" data-formatter="dateFormatter">Début</th>
                    <th data-field="date_fin" data-sortable="true" data-formatter="dateFormatter">Fin</th>
                    <th data-field="montant_initial" data-sortable="true" data-formatter="moneyFormatter">Initial</th>
                    <th data-field="montant_engage" data-sortable="true" data-formatter="moneyFormatter">Engagé</th>
                    <th data-field="montant_disponible" data-sortable="true" data-formatter="moneyFormatter">Disponible</th>
                    <th data-field="statut" data-sortable="true" data-formatter="statusFormatter">Statut</th>
                    <th data-field="actions" data-formatter="actionFormatter" data-events="actionEvents">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.budgets._modal')
@stop

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    const routes = {
        store: "{{ route('cores.referentiel.budgets.store') }}",
        update: "{{ route('cores.referentiel.budgets.update', ':id') }}",
        destroy: "{{ route('cores.referentiel.budgets.destroy', ':id') }}"
    };

    function dateFormatter(value) {
        if (!value) return '-';
        return new Date(value).toLocaleDateString('fr-FR');
    }

    function moneyFormatter(value) {
        if (!value) return '-';
        return parseFloat(value).toLocaleString('fr-FR', { style: 'currency', currency: 'XOF' });
    }

    function statusFormatter(value) {
        if (value === 'previsionnel') return '<span class="badge bg-secondary">Prévisionnel</span>';
        if (value === 'en_cours') return '<span class="badge bg-success">En Cours</span>';
        if (value === 'cloture') return '<span class="badge bg-danger">Clôturé</span>';
        return value;
    }

    function actionFormatter(value, row) {
        return `
            <button class="btn btn-xs btn-info edit-btn" title="Modifier"><i class="fas fa-edit"></i></button>
            <button class="btn btn-xs btn-danger delete-btn" title="Supprimer"><i class="fas fa-trash"></i></button>
        `;
    }

    window.actionEvents = {
        'click .edit-btn': function (e, value, row, index) {
            editItem(row);
        },
        'click .delete-btn': function (e, value, row, index) {
            deleteItem(row.id);
        }
    };

    $('#btn-add').click(function() {
        $('#form')[0].reset();
        $('#itemId').val('');
        $('#modalLabel').text('Nouveau Budget');
        $('#modal').modal('show');
    });

    function editItem(row) {
        $('#form')[0].reset();
        $('#itemId').val(row.id);
        $('#modalLabel').text('Modifier Budget');
        
        $('#code').val(row.code);
        $('#libelle').val(row.libelle);
        $('#exercice').val(row.exercice);
        $('#date_debut').val(row.date_debut); // Format must include YYYY-MM-DD
        $('#date_fin').val(row.date_fin);
        $('#montant_initial').val(row.montant_initial);
        $('#statut').val(row.statut);
        $('#responsable_id').val(row.responsable_id);
        $('#observations').val(row.observations);

        $('#modal').modal('show');
    }

    function saveItem() {
        let id = $('#itemId').val();
        let url = id ? routes.update.replace(':id', id) : routes.store;
        let method = id ? 'PUT' : 'POST';
        let data = $('#form').serialize();

        $.ajax({
            url: url,
            method: method,
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                if(response.success) {
                    $('#modal').modal('hide');
                    $('#table').bootstrapTable('refresh');
                    Swal.fire('Succès', response.message, 'success');
                }
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON.message || 'Une erreur est survenue', 'error');
            }
        });
    }

    function deleteItem(id) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible (désactivation)!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.destroy.replace(':id', id),
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(response) {
                        $('#table').bootstrapTable('refresh');
                        Swal.fire('Supprimé!', response.message, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Erreur', xhr.responseJSON.message || 'Une erreur est survenue', 'error');
                    }
                });
            }
        })
    }
</script>
@endpush
