/**
 * CategorieForm.js
 * Handles category form operations
 */
export class CategorieForm {
    constructor(modalSelector, formSelector, tableInstance) {
        this.$modal = $(modalSelector);
        this.$form = $(formSelector);
        this.tableInstance = tableInstance;
        this.init();
    }

    init() {
        this.$form.on('submit', (e) => this.handleSubmit(e));
    }

    open(categorieData = null) {
        this.$form[0].reset();

        if (categorieData) {
            // Edit mode
            $('#categorieModalLabel').text('Modifier la catégorie');
            $('#categorieId').val(categorieData.id);
            $('#categorieCode').val(categorieData.code);
            $('#categorieLibelle').val(categorieData.libelle);
            $('#categorieDescription').val(categorieData.description || '');
        } else {
            // Add mode
            $('#categorieModalLabel').text('Nouvelle catégorie');
            $('#categorieId').val('');
        }

        this.$modal.modal('show');
    }

    handleSubmit(e) {
        e.preventDefault();

        const id = $('#categorieId').val();
        const url = id
            ? `/cores/referentiel/categories/${id}`
            : '/cores/referentiel/categories';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: this.$form.serialize(),
            success: (response) => {
                if (response.success) {
                    this.$modal.modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: response.message,
                        timer: 2000
                    });
                    this.tableInstance.refresh();
                }
            },
            error: (xhr) => {
                let message = 'Une erreur est survenue';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    html: message
                });
            }
        });
    }
}
