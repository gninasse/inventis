@extends('core::layouts.master')

@section('header', 'Gestion des Fabricants & Marques')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
    <li class="breadcrumb-item active" aria-current="page">Référentiel</li>
    <li class="breadcrumb-item active" aria-current="page">Fabricants</li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-table/bootstrap-table.min.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des fabricants</h3>
    </div>
    <div class="card-body">
        <div id="toolbar">
            @can('cores.referentiel.fabricants.store')
            <button id="btn-add" class="btn btn-primary" data-bs-toggle="tooltip" title="Ajouter">
                <i class="fas fa-plus"></i> Nouveau
            </button>
            @endcan
        </div>
        <table id="table"
               data-toggle="table"
               data-url="{{ route('cores.referentiel.fabricants.data') }}"
               data-pagination="true"
               data-side-pagination="server"
               data-search="true"
               data-show-refresh="true"
               data-toolbar="#toolbar"
               data-id-field="id">
            <thead>
                <tr>
                    <th data-field="raison_sociale" data-sortable="true">Raison Sociale</th>
                    <th data-field="pays" data-sortable="true">Pays</th>
                    <th data-field="site_web" data-sortable="true" data-formatter="linkFormatter">Site Web</th>
                    <th data-field="marques_count" data-sortable="true">Marques</th>
                    <th data-field="actions" data-formatter="actionFormatter" data-events="actionEvents">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('core::referentiel.fabricants._modal')
@include('core::referentiel.fabricants._hierarchy_modal')

@stop

@push('js')
<script src="{{ asset('plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-table/locale/bootstrap-table-fr-FR.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    const routes = {
        store: "{{ route('cores.referentiel.fabricants.store') }}",
        update: "{{ route('cores.referentiel.fabricants.update', ':id') }}",
        destroy: "{{ route('cores.referentiel.fabricants.destroy', ':id') }}",
        show: "{{ route('cores.referentiel.fabricants.show', ':id') }}",
        storeMarque: "{{ route('cores.referentiel.fabricants.marques.store') }}",
        destroyMarque: "{{ route('cores.referentiel.fabricants.marques.destroy', ':id') }}",
        storeModele: "{{ route('cores.referentiel.fabricants.modeles.store') }}",
        destroyModele: "{{ route('cores.referentiel.fabricants.modeles.destroy', ':id') }}"
    };

    function linkFormatter(value) {
        if (!value) return '-';
        return `<a href="${value}" target="_blank"><i class="fas fa-external-link-alt"></i></a>`;
    }

    function actionFormatter(value, row) {
        return `
            <button class="btn btn-xs btn-info edit-btn" title="Modifier"><i class="fas fa-edit"></i></button>
            <button class="btn btn-xs btn-warning hierarchy-btn" title="Gérer Marques/Modèles"><i class="fas fa-sitemap"></i></button>
            <button class="btn btn-xs btn-danger delete-btn" title="Supprimer"><i class="fas fa-trash"></i></button>
        `;
    }

    window.actionEvents = {
        'click .edit-btn': function (e, value, row, index) {
            editItem(row);
        },
        'click .hierarchy-btn': function (e, value, row, index) {
            manageHierarchy(row);
        },
        'click .delete-btn': function (e, value, row, index) {
            deleteItem(row.id);
        }
    };

    $('#btn-add').click(function() {
        $('#form')[0].reset();
        $('#itemId').val('');
        $('#modalLabel').text('Nouveau Fabricant');
        $('#modal').modal('show');
    });

    function editItem(row) {
        $('#form')[0].reset();
        $('#itemId').val(row.id);
        $('#modalLabel').text('Modifier Fabricant');
        
        $('#raison_sociale').val(row.raison_sociale);
        $('#pays').val(row.pays);
        $('#site_web').val(row.site_web);

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
    
    // --- Hierarchy Logic ---
    function manageHierarchy(row) {
        $('#hierarchyModalTitle').text('Marques & Modèles : ' + row.raison_sociale);
        $('#fabricantIdForMarque').val(row.id);
        $('#hierarchyContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>');
        $('#hierarchyModal').modal('show');
        
        loadHierarchy(row.id);
    }
    
    function loadHierarchy(fabricantId) {
        $.ajax({
            url: routes.show.replace(':id', fabricantId),
            method: 'GET',
            success: function(response) {
                if(response.success) {
                    renderHierarchy(response.data);
                }
            }
        });
    }
    
    function renderHierarchy(fabricant) {
        let html = '';
        if(!fabricant.marques || fabricant.marques.length === 0) {
            html = '<p class="text-muted text-center mt-3">Aucune marque définie.</p>';
        } else {
            html += '<div class="accordion mt-3" id="accordionMarques">';
            fabricant.marques.forEach((marque, index) => {
                if(marque.actif) {
                    let collapseId = 'collapseMarque' + marque.id;
                    let headingId = 'headingMarque' + marque.id;
                    
                    html += `<div class="accordion-item">
                        <h2 class="accordion-header" id="${headingId}">
                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false" aria-controls="${collapseId}">
                                <strong>${marque.nom}</strong>
                            </button>
                        </h2>
                        <div id="${collapseId}" class="accordion-collapse collapse" aria-labelledby="${headingId}" data-bs-parent="#accordionMarques">
                            <div class="accordion-body py-2">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Modèles</span>
                                    <div>
                                        <button class="btn btn-xs btn-outline-danger me-2" onclick="deleteMarque(${marque.id}, ${fabricant.id})">Supprimer Marque</button>
                                    </div>
                                </div>
                                
                                <!-- Add Modele Form -->
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control" id="refModele_${marque.id}" placeholder="Référence">
                                    <input type="text" class="form-control w-50" id="desModele_${marque.id}" placeholder="Désignation">
                                    <button class="btn btn-success" type="button" onclick="saveModele(${marque.id}, ${fabricant.id})"><i class="fas fa-plus"></i></button>
                                </div>
                                
                                <!-- Modeles List -->
                                <ul class="list-group list-group-flush small">
                                    ${renderModeles(marque, fabricant.id)}
                                </ul>
                            </div>
                        </div>
                    </div>`;
                }
            });
            html += '</div>';
        }
        $('#hierarchyContent').html(html);
    }
    
    function renderModeles(marque, fabricantId) {
        if(!marque.modeles || marque.modeles.length === 0) return '<li class="list-group-item text-muted fst-italic">Aucun modèle.</li>';
        let html = '';
        marque.modeles.forEach(modele => {
            if(modele.actif) {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center py-1">
                    <span><strong>${modele.reference}</strong> - ${modele.designation}</span>
                    <button class="btn btn-xs btn-link text-danger p-0" onclick="deleteModele(${modele.id}, ${fabricantId})"><i class="fas fa-times"></i></button>
                </li>`;
            }
        });
        return html;
    }

    function saveMarque() {
        let fabricantId = $('#fabricantIdForMarque').val();
        let nom = $('#nomMarque').val();
        
        if(!nom) return;
        
        $.ajax({
            url: routes.storeMarque,
            method: 'POST',
            data: { fabricant_id: fabricantId, nom: nom },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                $('#nomMarque').val('');
                loadHierarchy(fabricantId);
                $('#table').bootstrapTable('refresh');
                Toast.fire({icon: 'success', title: 'Marque ajoutée'});
            },
            error: function(xhr) {
                 Swal.fire('Erreur', xhr.responseJSON.message, 'error');
            }
        });
    }
    
    function deleteMarque(id, fabricantId) {
        $.ajax({
            url: routes.destroyMarque.replace(':id', id),
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                loadHierarchy(fabricantId);
                $('#table').bootstrapTable('refresh');
            },
            error: function(xhr) { Swal.fire('Erreur', xhr.responseJSON.message, 'error'); }
        });
    }
    
    function saveModele(marqueId, fabricantId) {
        let ref = $(`#refModele_${marqueId}`).val();
        let des = $(`#desModele_${marqueId}`).val();
        
        if(!ref || !des) return;
        
        // Needed: famille_id is not requested in my controller validation but it's in Model fillable/migration. 
        // Migration said `famille_id` constrained but nullable? No, not nullable in my migration creation?
        // Let's check: $table->foreignId('famille_id')->nullable()->constrained('referentiel_familles');
        
        // I will assume nullable or I didn't add it in the inline form. 
        // In my `FabricantController/storeModele` validaton:
        // 'marque_id' => 'required', 'reference' => 'required', 'designation' => 'required'.
        // So famille_id is optional. Good.
        
        $.ajax({
            url: routes.storeModele,
            method: 'POST',
            data: { marque_id: marqueId, reference: ref, designation: des },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                loadHierarchy(fabricantId);
            },
            error: function(xhr) { Swal.fire('Erreur', xhr.responseJSON.message, 'error'); }
        });
    }
    
    function deleteModele(id, fabricantId) {
        $.ajax({
            url: routes.destroyModele.replace(':id', id),
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(response) {
                loadHierarchy(fabricantId);
            },
            error: function(xhr) { Swal.fire('Erreur', xhr.responseJSON.message, 'error'); }
        });
    }

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
</script>
@endpush
