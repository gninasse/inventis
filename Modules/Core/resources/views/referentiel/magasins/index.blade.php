@extends('core::layouts.master')

@section('header', 'Gestion des Magasins')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Magasins</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des magasins</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.magasins.store')
            <button id="btn-add" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter">
                <i class="fas fa-plus"></i> Nouveau
            </button>
            @endcan
        </div>
        <table id="table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.magasins.data') }}"
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
                    <th data-field="type" data-sortable="true" data-formatter="typeFormatter">Type</th>
                    <th data-field="emplacements_count" data-sortable="true">Emplacements</th>
                    <th data-field="actions" data-formatter="actionFormatter" data-events="actionEvents">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.magasins._modal')
@include('core::referentiel.magasins._emplacements_modal')
@stop

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    const routes = {
        store: "{{ route('cores.referentiel.magasins.store') }}",
        update: "{{ route('cores.referentiel.magasins.update', ':id') }}",
        destroy: "{{ route('cores.referentiel.magasins.destroy', ':id') }}",
        show: "{{ route('cores.referentiel.magasins.show', ':id') }}",
        storeEmplacement: "{{ route('cores.referentiel.magasins.emplacements.store') }}",
        destroyEmplacement: "{{ route('cores.referentiel.magasins.emplacements.destroy', ':id') }}"
    };

    function typeFormatter(value) {
        if (value === 'central') return '<span class="badge bg-primary">Central</span>';
        if (value === 'annexe') return '<span class="badge bg-info">Annexe</span>';
        if (value === 'pharmacie') return '<span class="badge bg-success">Pharmacie</span>';
        return value;
    }

    function actionFormatter(value, row) {
        return `
            <button class="btn btn-xs btn-info edit-btn" title="Modifier"><i class="fas fa-edit"></i></button>
            <button class="btn btn-xs btn-warning emplacements-btn" title="Gérer Emplacements"><i class="fas fa-th"></i></button>
            <button class="btn btn-xs btn-danger delete-btn" title="Supprimer"><i class="fas fa-trash"></i></button>
        `;
    }

    window.actionEvents = {
        'click .edit-btn': function (e, value, row, index) {
            editItem(row);
        },
        'click .emplacements-btn': function (e, value, row, index) {
            manageEmplacements(row);
        },
        'click .delete-btn': function (e, value, row, index) {
            deleteItem(row.id);
        }
    };

    $('#btn-add').click(function() {
        $('#form')[0].reset();
        $('#itemId').val('');
        $('#modalLabel').text('Nouveau Magasin');
        $('#modal').modal('show');
    });

    function editItem(row) {
        $('#form')[0].reset();
        $('#itemId').val(row.id);
        $('#modalLabel').text('Modifier Magasin');
        
        $('#code').val(row.code);
        $('#libelle').val(row.libelle);
        $('#type').val(row.type);
        $('#responsable_id').val(row.responsable_id);
        $('#adresse').val(row.adresse);

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
    
    // --- Emplacements Logic ---
    function manageEmplacements(row) {
        $('#emplacementsModalTitle').text('Emplacements du magasin : ' + row.libelle);
        $('#emplacementMagasinId').val(row.id);
        $('#emplacementsList').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
        $('#emplacementsModal').modal('show');
        
        loadEmplacements(row.id);
    }
    
    function loadEmplacements(magasinId) {
        $.ajax({
            url: routes.show.replace(':id', magasinId),
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    renderEmplacements(response.data);
                }
            }
        });
    }
    
    function renderEmplacements(magasin) {
        let html = '';
        if(magasin.emplacements.length === 0) {
            html = '<p class="text-muted text-center">Aucun emplacement défini.</p>';
        } else {
            html = '<table class="table table-sm table-striped"><thead><tr><th>Code</th><th>Libellé</th><th>Capacité</th><th>Action</th></tr></thead><tbody>';
            magasin.emplacements.forEach(emp => {
                if(emp.actif) {
                html += `<tr>
                    <td>${emp.code}</td>
                    <td>${emp.libelle}</td>
                    <td>${emp.capacite_max || '-'}</td>
                    <td><button class="btn btn-xs btn-danger" onclick="deleteEmplacement(${emp.id}, ${magasin.id})"><i class="fas fa-trash"></i></button></td>
                </tr>`;
                }
            });
            html += '</tbody></table>';
        }
        $('#emplacementsList').html(html);
    }
    
    function saveEmplacement() {
        let data = $('#emplacementForm').serialize();
        let magasinId = $('#emplacementMagasinId').val();
         $.ajax({
            url: routes.storeEmplacement,
            method: 'POST',
            data: data,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                if(response.success) {
                    $('#emplacementForm')[0].reset();
                    $('#emplacementMagasinId').val(magasinId); // Restore ID
                    loadEmplacements(magasinId);
                    $('#table').bootstrapTable('refresh');
                    Toast.fire({icon: 'success', title: 'Emplacement ajouté'});
                }
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON.message || 'Une erreur est survenue', 'error');
            }
        });
    }
    
    function deleteEmplacement(id, magasinId) {
        $.ajax({
            url: routes.destroyEmplacement.replace(':id', id),
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                loadEmplacements(magasinId);
                $('#table').bootstrapTable('refresh');
            },
            error: function(xhr) {
                Swal.fire('Erreur', xhr.responseJSON.message || 'Une erreur est survenue', 'error');
            }
        });
    }
    
    // Toast helper if not exists
    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });

</script>
@endpush
