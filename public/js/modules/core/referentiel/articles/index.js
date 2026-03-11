/**
 * index.js
 * Entry point for Articles management
 */
import { ArticleForm } from './ArticleForm.js';
import { ArticleActions } from './ArticleActions.js';

$(function () {
    // ---- 1. Bootstrap Table Configuration ----
    const $table = $('#articles-table');

    // Formatters
    window.dateFormatter = function (value) {
        if (!value) return '-';
        return new Date(value).toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    window.typeFormatter = function (value) {
        if (value === 'durable') return `<span class="badge bg-primary">Durable</span>`;
        if (value === 'consommable') return `<span class="badge bg-secondary">Consommable</span>`;
        return value;
    }

    window.statusFormatter = function(value, row) {
        if (row.actif) {
            return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Actif</span>';
        }
        return '<span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Inactif</span>';
    };

    // Table instance helper
    const tableInstance = {
        refresh: () => $table.bootstrapTable('refresh'),
        getSelectedId: () => {
            const selections = $table.bootstrapTable('getSelections');
            if (selections.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: 'Veuillez sélectionner une ligne'
                });
                return null;
            }
            return selections[0].id;
        }
    };

    // Initialize Form and Actions
    const articleForm = new ArticleForm('#articleModal', '#articleForm', tableInstance);
    new ArticleActions(tableInstance, articleForm);

    // Enable/Disable buttons on selection
    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        const selections = $table.bootstrapTable('getSelections');
        const hasSelection = selections.length > 0;
        const isSingleSelection = selections.length === 1;

        $('#btn-edit-article').prop('disabled', !isSingleSelection);
        $('#btn-toggle-status').prop('disabled', !isSingleSelection);
        $('#btn-delete-article').prop('disabled', !hasSelection);
    });

    // Toggle Status Handler
    $('#btn-toggle-status').on('click', function() {
        const id = tableInstance.getSelectedId();
        if (!id) return;

        const selections = $table.bootstrapTable('getSelections');
        const actif = selections[0].actif;
        const action = actif ? 'désactiver' : 'activer';

        Swal.fire({
            title: 'Confirmer l\'action',
            text: `Voulez-vous vraiment ${action} cet article ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#ffc107',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route('cores.referentiel.articles.toggle-status', id),
                    type: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            tableInstance.refresh();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erreur',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: xhr.responseJSON?.message || 'Une erreur est survenue'
                        });
                    }
                });
            }
        });
    });
});
