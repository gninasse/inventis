/**
 * SousCategorieForm.js
 * Handles subcategory form operations
 */
export class SousCategorieForm {
    constructor(modalSelector, formSelector, tableInstance) {
        this.$modal = $(modalSelector);
        this.$form = $(formSelector);
        this.tableInstance = tableInstance;
        this.init();
    }

    init() {
        this.$form.on('submit', (e) => this.handleSubmit(e));
    }

    open(sousCategorieData = null) {
        this.$form[0].reset();

        if (sousCategorieData) {
            // Edit mode
            $('#sousCategorieModalLabel').text('Modifier la sous-catégorie');
            $('#sousCategorieId').val(sousCategorieData.id);
            $('#sousCategorieCategorie').val(sousCategorieData.categorie_id);
            $('#sousCategorieCode').val(sousCategorieData.code);
            $('#sousCategorieLibelle').val(sousCategorieData.libelle);
        } else {
            // Add mode
            $('#sousCategorieModalLabel').text('Nouvelle sous-catégorie');
            $('#sousCategorieId').val('');
        }

        this.$modal.modal('show');
    }

    handleSubmit(e) {
        e.preventDefault();

        const id = $('#sousCategorieId').val();
        const url = id
            ? `/cores/referentiel/sous-categories/${id}`
            : '/cores/referentiel/sous-categories';
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
