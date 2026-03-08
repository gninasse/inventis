@extends('core::layouts.master')

@section('header', 'Modes d\'Acquisition')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Modes d'Acquisition</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des modes d'acquisition</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.modes-acquisition.store')
            <button id="btn-add" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter">
                <i class="fas fa-plus"></i> Nouveau
            </button>
            @endcan
        </div>
        <table id="table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.modes-acquisition.data') }}"
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
                    <th data-field="description" data-sortable="true">Description</th>
                    <th data-field="actions" data-formatter="actionFormatter" data-events="actionEvents">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.modes-acquisition._modal')
@stop

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    const routes = {
        store: "{{ route('cores.referentiel.modes-acquisition.store') }}",
        update: "{{ route('cores.referentiel.modes-acquisition.update', ':id') }}",
        destroy: "{{ route('cores.referentiel.modes-acquisition.destroy', ':id') }}"
    };

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
        $('#pieces_requises').val([]).trigger('change'); // Reset multi-select if using select2, here standard
        
        // Manual reset for multi-select
        $('#pieces_requises option').prop('selected', false);

        $('#modalLabel').text('Nouveau Mode');
        $('#modal').modal('show');
    });

    function editItem(row) {
        $('#form')[0].reset();
        $('#itemId').val(row.id);
        $('#modalLabel').text('Modifier Mode');
        
        $('#code').val(row.code);
        $('#libelle').val(row.libelle);
        $('#description').val(row.description);
        
        // Handle multi-select pieces_requises (array of strings)
        if(row.pieces_requises) {
            let values = row.pieces_requises;
            // Select match options
            $('#pieces_requises').val(values);
        } else {
             $('#pieces_requises').val([]);
        }

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
