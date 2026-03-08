/**
 * FournisseurActions.js
 * Handles Edit and Delete actions for Fournisseurs.
 */
export class FournisseurActions {
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
            const fournisseurId = this.table.getSelectedId();
            if (fournisseurId) this.editFournisseur(fournisseurId);
        });

        $('#btn-delete').click(() => {
            const fournisseurId = this.table.getSelectedId();
            if (fournisseurId) this.deleteFournisseur(fournisseurId);
        });
    }

    editFournisseur(fournisseurId) {
        $.ajax({
            url: route('cores.referentiel.fournisseurs.show', fournisseurId),
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

    deleteFournisseur(fournisseurId) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action va désactiver ce fournisseur (soft delete)",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route('cores.referentiel.fournisseurs.destroy', fournisseurId),
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
