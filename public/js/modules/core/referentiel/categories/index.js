/**
 * index.js
 * Entry point for Categories management
 */
import { CategorieForm } from './CategorieForm.js';
import { CategorieActions } from './CategorieActions.js';

$(function () {
    // ---- Bootstrap Table Configuration ----
    const $table = $('#categories-table');

    // Date formatter
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

    // Actif formatter
    window.actifFormatter = function (value) {
        return value
            ? '<span class="badge bg-success">Actif</span>'
            : '<span class="badge bg-danger">Inactif</span>';
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
    const categorieForm = new CategorieForm('#categorieModal', '#categorieForm', tableInstance);
    new CategorieActions(tableInstance, categorieForm);

    // Enable/Disable buttons on selection
    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        const selections = $table.bootstrapTable('getSelections');
        const hasSelection = selections.length > 0;
        const isSingleSelection = selections.length === 1;

        $('#btn-edit-categorie').prop('disabled', !isSingleSelection);
        $('#btn-delete-categorie').prop('disabled', !hasSelection);
    });
});
