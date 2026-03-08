/**
 * FournisseurForm.js
 * Handles Modal, Form Validation, and Submission for Fournisseurs.
 */
export class FournisseurForm {
    constructor(modalSelector, formSelector, tableInstance) {
        this.$modal = $(modalSelector);
        this.$form = $(formSelector);
        this.table = tableInstance;
        this.init();
    }

    init() {
        this.initValidation();
        this.initSubmission();
    }

    initValidation() {
        $('input[required], select[required]', this.$form).on('invalid', function (e) {
            e.preventDefault();
            this.setCustomValidity('');

            if (this.validity.valueMissing) {
                this.setCustomValidity('Veuillez remplir ce champ.');
            }
        });

        $('input, select', this.$form).on('input change', function () {
            this.setCustomValidity('');
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
    }

    openForAdd() {
        this.resetForm();
        $('#modalLabel').text('Nouveau Fournisseur');
        $('#fournisseur_id').val('');
        this.$modal.modal('show');
    }

    openForEdit(data) {
        $('#modalLabel').text('Modifier Fournisseur');
        $('#fournisseur_id').val(data.id);
        $('#raison_sociale').val(data.raison_sociale);
        $('#type').val(data.type);
        $('#sigle').val(data.sigle);
        $('#adresse').val(data.adresse);
        $('#telephone').val(data.telephone);
        $('#email').val(data.email);
        $('#ifu').val(data.ifu);
        $('#rccm').val(data.rccm);
        $('#pays').val(data.pays);
        $('#contact_nom').val(data.contact_nom);
        $('#contact_telephone').val(data.contact_telephone);
        this.$modal.modal('show');
    }

    initSubmission() {
        $('#btn-save-fournisseur').click(() => {
            if (!this.validateForm()) {
                return false;
            }

            const fournisseurId = $('#fournisseur_id').val();
            const url = fournisseurId 
                ? route('cores.referentiel.fournisseurs.update', fournisseurId) 
                : route('cores.referentiel.fournisseurs.store');
            const method = fournisseurId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: this.$form.serialize(),
                beforeSend: () => {
                    $('#btn-save-fournisseur').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
                },
                success: (response) => {
                    if (response.success) {
                        this.$modal.modal('hide');
                        this.table.refresh();
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message,
                            timer: 2000
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
                    $('#btn-save-fournisseur').prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
                }
            });
        });
    }

    validateForm() {
        this.clearErrors();
        let isValid = true;
        const errors = {};

        const checkEmpty = (selector, field, msg) => {
            if ($(selector).val().trim() === '') {
                errors[field] = [msg];
                return false;
            }
            return true;
        };

        if (!checkEmpty('#raison_sociale', 'raison_sociale', 'La raison sociale est obligatoire')) isValid = false;
        if (!checkEmpty('#type', 'type', 'Le type est obligatoire')) isValid = false;
        if (!checkEmpty('#pays', 'pays', 'Le pays est obligatoire')) isValid = false;

        const email = $('#email').val().trim();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors['email'] = ['L\'email n\'est pas valide'];
            isValid = false;
        }

        if (!isValid) {
            this.displayErrors(errors);
        }

        return isValid;
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
    }
}
