/**
 * SourceActions.js
 * Handles Edit and Delete actions for Sources de Financement.
 */
export class SourceActions {
    constructor(tableInstance, formInstance) {
        this.table = tableInstance;
        this.form = formInstance;
        this.initButtons();
    }

    initButtons() {
        $('#btn-add').click(() => {
            this.form.openForAdd();
        });

        $('#btn-edit').click(() => {
            const sourceId = this.table.getSelectedId();
            if (sourceId) this.editSource(sourceId);
        });

        $('#btn-delete').click(() => {
            const sourceId = this.table.getSelectedId();
            if (sourceId) this.deleteSource(sourceId);
        });
    }

    editSource(sourceId) {
        $.ajax({
            url: route('cores.referentiel.sources.show', sourceId),
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    this.form.openForEdit(response.data);
                }
            },
            error: (xhr) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de charger les données'
                });
            }
        });
    }

    deleteSource(sourceId) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action va désactiver cette source de financement",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route('cores.referentiel.sources.destroy', sourceId),
                    method: 'DELETE',
                    success: (response) => {
                        if (response.success) {
                            this.table.refresh();
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé',
                                text: response.message,
                                timer: 2000
                            });
                        }
                    },
                    error: (xhr) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: xhr.responseJSON.message || 'Erreur lors de la suppression'
                        });
                    }
                });
            }
        });
    }
}
