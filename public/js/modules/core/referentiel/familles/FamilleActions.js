/**
 * FamilleActions.js
 * Handles famille action buttons (add, edit, delete)
 */
export class FamilleActions {
    constructor(tableInstance, formInstance) {
        this.tableInstance = tableInstance;
        this.formInstance = formInstance;
        this.init();
    }

    init() {
        $('#btn-add-famille').on('click', () => this.handleAdd());
        $('#btn-edit-famille').on('click', () => this.handleEdit());
        $('#btn-delete-famille').on('click', () => this.handleDelete());
    }

    handleAdd() {
        this.formInstance.open();
    }

    handleEdit() {
        const id = this.tableInstance.getSelectedId();
        if (!id) return;

        $.ajax({
            url: `/cores/referentiel/familles/${id}`,
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    this.formInstance.open(response.data);
                }
            },
            error: () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de charger les données'
                });
            }
        });
    }

    handleDelete() {
        const id = this.tableInstance.getSelectedId();
        if (!id) return;

        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action désactivera la famille!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/cores/referentiel/familles/${id}`,
                    method: 'DELETE',
                    success: (response) => {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Supprimé!',
                                text: response.message,
                                timer: 2000
                            });
                            this.tableInstance.refresh();
                        }
                    },
                    error: (xhr) => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: xhr.responseJSON?.message || 'Une erreur est survenue'
                        });
                    }
                });
            }
        });
    }
}
