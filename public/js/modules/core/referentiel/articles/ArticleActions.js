/**
 * ArticleActions.js
 * Handles Edit and Delete actions for Articles.
 */
export class ArticleActions {
    constructor(tableInstance, formInstance) {
        this.table = tableInstance;
        this.form = formInstance;
        this.initButtons();
    }

    initButtons() {
        $('#btn-add-article').click(() => {
            this.form.openForAdd();
        });

        $('#btn-edit-article').click(() => {
            const articleId = this.table.getSelectedId();
            if (articleId) this.editArticle(articleId);
        });

        $('#btn-delete-article').click(() => {
            const articleId = this.table.getSelectedId();
            if (articleId) this.deleteArticle(articleId);
        });
    }

    editArticle(articleId) {
        $.ajax({
            url: route('cores.referentiel.articles.show', articleId),
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    this.form.openForEdit(articleId, response.data);
                }
            },
            error: (xhr) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: "Impossible de charger les données de l'article."
                });
            }
        });
    }

    deleteArticle(articleId) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Cette action est irréversible (l'article sera désactivé) !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route('cores.referentiel.articles.destroy', articleId),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                            text: xhr.responseJSON.message || 'Erreur lors de la suppression.'
                        });
                    }
                });
            }
        });
    }
}
