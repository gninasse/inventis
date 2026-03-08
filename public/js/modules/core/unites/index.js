/**
 * index.js
 * Entry point for Unites management
 */
import { UniteForm } from './UniteForm.js';
import { UniteActions } from './UniteActions.js';

$(function () {
    const $table = $('#unites-table');

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

    const uniteForm = new UniteForm('#createUniteModal', '#uniteForm', tableInstance);
    new UniteActions(tableInstance, uniteForm);

    $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
        const selections = $table.bootstrapTable('getSelections');
        const isSingleSelection = selections.length === 1;

        $('#btn-edit').prop('disabled', !isSingleSelection);
        $('#btn-delete').prop('disabled', !isSingleSelection);
    });

    // Filtres
    $('#filter_site_id').on('change', function () {
        loadDirections($(this).val(), '#filter_direction_id');
        $('#filter_service_id').html('<option value="">Tous les services</option>').prop('disabled', true);
        refreshTable();
    });

    $('#filter_direction_id').on('change', function () {
        loadServices($(this).val(), '#filter_service_id');
        refreshTable();
    });

    $('#filter_service_id').on('change', function () {
        refreshTable();
    });

    function refreshTable() {
        const siteId = $('#filter_site_id').val();
        const directionId = $('#filter_direction_id').val();
        const serviceId = $('#filter_service_id').val();
        let url = window.uniteRoutes.data;
        const params = [];
        if (siteId) params.push(`site_id=${siteId}`);
        if (directionId) params.push(`direction_id=${directionId}`);
        if (serviceId) params.push(`service_id=${serviceId}`);
        if (params.length > 0) url += '?' + params.join('&');
        $table.bootstrapTable('refresh', { url: url });
    }

    window.loadDirections = function(siteId, targetSelect, selectedId = null) {
        if (!siteId) {
            $(targetSelect).html('<option value="">Toutes les directions</option>').prop('disabled', true);
            return;
        }
        const url = window.uniteRoutes.directionsBySite.replace(':siteId', siteId);
        $.get(url, function(data) {
            let options = '<option value="">Toutes les directions</option>';
            data.forEach(function(dir) {
                let selected = selectedId == dir.id ? 'selected' : '';
                options += `<option value="${dir.id}" ${selected}>${dir.libelle}</option>`;
            });
            $(targetSelect).html(options).prop('disabled', false);
        });
    };

    window.loadServices = function(directionId, targetSelect, selectedId = null) {
        if (!directionId) {
            $(targetSelect).html('<option value="">Tous les services</option>').prop('disabled', true);
            return;
        }
        const url = window.uniteRoutes.servicesByDirection.replace(':directionId', directionId);
        $.get(url, function(data) {
            let options = '<option value="">Tous les services</option>';
            data.forEach(function(svc) {
                let selected = selectedId == svc.id ? 'selected' : '';
                options += `<option value="${svc.id}" ${selected}>${svc.libelle}</option>`;
            });
            $(targetSelect).html(options).prop('disabled', false);
        });
    };
});
