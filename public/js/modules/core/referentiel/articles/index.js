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
        $('#btn-delete-article').prop('disabled', !hasSelection);
    });
});
