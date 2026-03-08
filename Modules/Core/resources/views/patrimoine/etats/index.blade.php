@extends('core::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">États des Biens</h4>
                    @can('cores.patrimoine.etats.store')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEtatModal">
                        <i class="fas fa-plus"></i> Nouvel État
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="etats-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Ordre</th>
                                <th>Code</th>
                                <th>Badge</th>
                                <th>Libellé</th>
                                <th>Déclencheur Réforme</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('core::patrimoine.etats._modal')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#etats-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('cores.patrimoine.etats.data') }}",
        columns: [
            {data: 'ordre', name: 'ordre'},
            {data: 'code', name: 'code'},
            {data: 'badge', name: 'badge', orderable: false, searchable: false},
            {data: 'libelle', name: 'libelle'},
            {data: 'reforme_html', name: 'declencheur_reforme'},
            {
                data: 'actif', 
                name: 'actif',
                render: function(data) {
                    return data ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-danger">Inactif</span>';
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']]
    });

    // Color/Text Preview
    function updatePreview() {
        var color = $('#couleur').val();
        var icon = $('#icone').val();
        var label = $('#libelle').val() || 'Aperçu';
        
        var iconHtml = icon ? `<i class="${icon} me-1"></i>` : '';
        $('#badge-preview').html(`<span class="badge" style="background-color: ${color}; color: #fff;">${iconHtml} ${label}</span>`);
    }

    $('#couleur, #icone, #libelle').on('input change', updatePreview);

    // EDIT
    $('#etats-table').on('click', '.edit-etat', function() {
        var id = $(this).data('id');
        $.get("{{ route('cores.patrimoine.etats.index') }}/" + id, function(res) {
            if(res.success) {
                var data = res.data;
                $('#etat_id').val(data.id);
                $('#code').val(data.code).prop('readonly', data.is_system);
                $('#libelle').val(data.libelle);
                $('#description').val(data.description);
                $('#couleur').val(data.couleur);
                $('#icone').val(data.icone);
                $('#actif').prop('checked', data.actif);
                $('#declencheur_reforme').prop('checked', data.declencheur_reforme);
                
                $('#createEtatModalLabel').text('Modifier l\'État');
                updatePreview();
                $('#createEtatModal').modal('show');
            }
        });
    });

    // DELETE
    $('#etats-table').on('click', '.delete-etat', function() {
        var id = $(this).data('id');
        if(confirm('Voulez-vous vraiment désactiver cet état ?')) {
            $.ajax({
                url: "{{ route('cores.patrimoine.etats.index') }}/" + id,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    if(res.success) {
                        table.ajax.reload();
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Erreur lors de la suppression');
                }
            });
        }
    });

    // RESET MODAL
    $('#createEtatModal').on('hidden.bs.modal', function() {
        $('#etatForm')[0].reset();
        $('#etat_id').val('');
        $('#code').prop('readonly', false);
        $('#createEtatModalLabel').text('Nouvel État');
        updatePreview();
    });

    // SUBMIT FORM
    $('#etatForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#etat_id').val();
        var url = id ? "{{ route('cores.patrimoine.etats.index') }}/" + id : "{{ route('cores.patrimoine.etats.store') }}";
        var method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    $('#createEtatModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(res.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if(errors) {
                    Object.values(errors).forEach(err => toastr.error(err[0]));
                } else {
                    toastr.error('Une erreur est survenue');
                }
            }
        });
    });
});
</script>
@endpush
