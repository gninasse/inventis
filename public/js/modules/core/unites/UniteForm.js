/**
 * UniteForm.js
 * Handles Modal, Form Validation, and Submission for Unites.
 */
export class UniteForm {
    constructor(modalSelector, formSelector, tableInstance) {
        this.$modal = $(modalSelector);
        this.$form = $(formSelector);
        this.table = tableInstance;
        this.init();
    }

    init() {
        this.initValidation();
        this.initSubmission();
        this.initCascading();
        this.loadMajors();
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

    initCascading() {
        $('#c_site_id').on('change', () => {
            this.loadDirectionsForModal();
        });

        $('#c_direction_id').on('change', () => {
            this.loadServicesForModal();
        });
    }

    loadDirectionsForModal(selectedDirectionId = null) {
        const siteId = $('#c_site_id').val();
        if (!siteId) {
            $('#c_direction_id').html('<option value="">Sélectionner d\'abord un site</option>').prop('disabled', true);
            return;
        }
        const url = window.uniteRoutes.directionsBySite.replace(':siteId', siteId);
        $.get(url, (data) => {
            let options = '<option value="">Sélectionner une direction</option>';
            data.forEach((dir) => {
                let selected = selectedDirectionId == dir.id ? 'selected' : '';
                options += `<option value="${dir.id}" ${selected}>${dir.libelle}</option>`;
            });
            $('#c_direction_id').html(options).prop('disabled', false);
        });
    }

    loadServicesForModal(selectedServiceId = null) {
        const directionId = $('#c_direction_id').val();
        if (!directionId) {
            $('#c_service_id').html('<option value="">Sélectionner d\'abord une direction</option>').prop('disabled', true);
            return;
        }
        const url = window.uniteRoutes.servicesByDirection.replace(':directionId', directionId);
        $.get(url, (data) => {
            let options = '<option value="">Sélectionner un service</option>';
            data.forEach((svc) => {
                let selected = selectedServiceId == svc.id ? 'selected' : '';
                options += `<option value="${svc.id}" ${selected}>${svc.libelle}</option>`;
            });
            $('#c_service_id').html(options).prop('disabled', false);
        });
    }

    loadMajors(selectedMajorId = null) {
        $.get(window.uniteRoutes.majors, (users) => {
            let options = '<option value="">Sélectionner un major</option>';
            users.forEach((user) => {
                let selected = selectedMajorId == user.id ? 'selected' : '';
                options += `<option value="${user.id}" ${selected}>${user.name}</option>`;
            });
            $('#major_id').html(options);
        });
    }

    openForAdd() {
        this.resetForm();
        $('#createUniteModalLabel').text('Nouvelle Unité');
        $('#unite_id').val('');
        this.$modal.modal('show');
    }

    openForEdit(data) {
        $('#createUniteModalLabel').text('Modifier l\'Unité');
        $('#unite_id').val(data.id);
        $('#c_site_id').val(data.site_id);
        $('#code').val(data.code);
        $('#libelle').val(data.libelle);
        
        // Charger les directions du site
        this.loadDirectionsForModal(data.service?.direction_id);
        
        // Charger les services de la direction
        const self = this;
        setTimeout(() => {
            self.loadServicesForModal(data.service_id);
            $('#major_id').val(data.major_id);
        }, 200);
        
        this.$modal.modal('show');
    }

    initSubmission() {
        $('#uniteForm').on('submit', (e) => {
            e.preventDefault();

            if (!this.validateForm()) {
                return false;
            }

            const uniteId = $('#unite_id').val();
            const url = uniteId 
                ? route('cores.organisation.unites.update', uniteId) 
                : route('cores.organisation.unites.store');
            const method = uniteId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: this.$form.serialize(),
                beforeSend: () => {
                    $('#btn-save-unite').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
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
                    $('#btn-save-unite').prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
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

        if (!checkEmpty('#c_service_id', 'service_id', 'Le service est obligatoire')) isValid = false;
        if (!checkEmpty('#code', 'code', 'Le code est obligatoire')) isValid = false;
        if (!checkEmpty('#libelle', 'libelle', 'Le libellé est obligatoire')) isValid = false;

        if (!isValid) {
            this.displayErrors(errors);
        }

        return isValid;
    }

    displayErrors(errors) {
        this.clearErrors();
        $.each(errors, (field, messages) => {
            const $field = $(`#${field}`);
            if ($field.length) {
                $field.addClass('is-invalid');
                $field.after(`<div class="invalid-feedback d-block">${messages[0]}</div>`);
            }
        });
    }

    clearErrors() {
        $('.is-invalid', this.$form).removeClass('is-invalid');
        $('.invalid-feedback', this.$form).remove();
    }

    resetForm() {
        this.$form[0].reset();
        this.clearErrors();
        $('#c_direction_id').html('<option value="">Sélectionner d\'abord un site</option>').prop('disabled', true);
        $('#c_service_id').html('<option value="">Sélectionner d\'abord une direction</option>').prop('disabled', true);
        this.loadMajors();
    }
}
