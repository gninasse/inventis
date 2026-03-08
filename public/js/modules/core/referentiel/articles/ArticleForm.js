/**
 * ArticleForm.js
 * Handles Modal, Form Validation, and Submission for Articles.
 */
export class ArticleForm {
    constructor(modalSelector, formSelector, tableInstance) {
        this.$modal = $(modalSelector);
        this.$form = $(formSelector);
        this.table = tableInstance;
        this.init();
    }

    init() {
        this.initValidation();
        this.initSubmission();
        this.initDependentSelects();
    }

    initValidation() {
        // Remove 'is-invalid' class on input
        $('input, select, textarea', this.$form).on('input change', function () {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    }

    openForAdd() {
        this.resetForm();
        $('#modalTitle').text('Ajouter un article');
        $('#article_id').val('');
        this.loadCategories();
        this.$modal.modal('show');
    }

    openForEdit(articleId, data) {
        this.resetForm();
        $('#modalTitle').text('Modifier un article');
        $('#article_id').val(articleId);

        // Populate simple fields
        $('#code_national').val(data.code_national);
        $('#designation').val(data.designation);
        $('#description').val(data.description);
        $('#type').val(data.type);

        // Handle dependent selects
        this.loadCategories(data.categorie_id, () => {
            this.loadSousCategories(data.categorie_id, data.sous_categorie_id, () => {
                this.loadFamilles(data.sous_categorie_id, data.famille_id);
            });
        });

        this.$modal.modal('show');
    }

    initSubmission() {
        this.$form.submit((e) => {
            e.preventDefault();

            const articleId = $('#article_id').val();
            const url = articleId ? route('cores.referentiel.articles.update', articleId) : route('cores.referentiel.articles.store');
            const method = articleId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: this.$form.serialize(),
                beforeSend: () => {
                    $('#btn-save').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
                },
                success: (response) => {
                    if (response.success) {
                        this.$modal.modal('hide');
                        this.table.refresh();
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },
                error: (xhr) => {
                    if (xhr.status === 422) {
                        this.displayErrors(xhr.responseJSON.errors);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: xhr.responseJSON.message || 'Une erreur est survenue'
                        });
                    }
                },
                complete: () => {
                    $('#btn-save').prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
                }
            });
        });

        // Trigger form submission from the modal's save button
        $('#btn-save').click(() => this.$form.submit());
    }

    initDependentSelects() {
        $('#categorie_id').change(() => {
            const categorieId = $('#categorie_id').val();
            this.loadSousCategories(categorieId);
        });

        $('#sous_categorie_id').change(() => {
            const sousCategorieId = $('#sous_categorie_id').val();
            this.loadFamilles(sousCategorieId);
        });
    }

    loadCategories(selectedValue = null, callback = null) {
        const $select = $('#categorie_id');
        this.resetSelect($select, 'Sélectionner une catégorie');

        $.ajax({
            url: route('cores.referentiel.articles.categories'),
            success: (data) => {
                data.forEach(item => {
                    $select.append(new Option(item.libelle, item.id));
                });
                if (selectedValue) {
                    $select.val(selectedValue);
                }
                if (callback) callback();
            }
        });
    }

    loadSousCategories(categorieId, selectedValue = null, callback = null) {
        const $select = $('#sous_categorie_id');
        this.resetSelect($select, 'Sélectionner une sous-catégorie');
        $select.prop('disabled', true);

        if (!categorieId) return;

        $.ajax({
            url: route('cores.referentiel.articles.sous-categories', { categorieId: categorieId }),
            success: (data) => {
                $select.prop('disabled', false);
                data.forEach(item => {
                    $select.append(new Option(item.libelle, item.id));
                });
                if (selectedValue) {
                    $select.val(selectedValue);
                }
                if (callback) callback();
            }
        });
    }

    loadFamilles(sousCategorieId, selectedValue = null, callback = null) {
        const $select = $('#famille_id');
        this.resetSelect($select, 'Sélectionner une famille');
        $select.prop('disabled', true);

        if (!sousCategorieId) return;

        $.ajax({
            url: route('cores.referentiel.articles.familles', { sousCategorieId: sousCategorieId }),
            success: (data) => {
                $select.prop('disabled', false);
                data.forEach(item => {
                    $select.append(new Option(item.libelle, item.id));
                });
                if (selectedValue) {
                    $select.val(selectedValue);
                }
                if (callback) callback();
            }
        });
    }

    displayErrors(errors) {
        this.clearErrors();
        $.each(errors, (field, messages) => {
            const $field = $(`#${field}`);
            $field.addClass('is-invalid');
            $field.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
        });
    }

    clearErrors() {
        $('.is-invalid', this.$form).removeClass('is-invalid');
        $('.invalid-feedback', this.$form).remove();
    }

    resetForm() {
        this.$form[0].reset();
        this.clearErrors();
        this.resetSelect($('#categorie_id'), 'Sélectionner une catégorie');
        this.resetSelect($('#sous_categorie_id'), 'Sélectionner une sous-catégorie', true);
        this.resetSelect($('#famille_id'), 'Sélectionner une famille', true);
    }

    resetSelect($select, placeholder, disabled = false) {
        $select.html(`<option value="">${placeholder}</option>`).prop('disabled', disabled);
    }
}
