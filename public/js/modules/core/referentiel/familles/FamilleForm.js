/**
 * FamilleForm.js
 * Handles famille form operations with cascading dropdowns
 */
export class FamilleForm {
    constructor(modalSelector, formSelector, tableInstance) {
        this.$modal = $(modalSelector);
        this.$form = $(formSelector);
        this.tableInstance = tableInstance;
        this.init();
    }

    init() {
        this.$form.on('submit', (e) => this.handleSubmit(e));

        // Cascading dropdown: when category changes, load subcategories
        $('#familleCategorie').on('change', (e) => this.loadSousCategories(e.target.value));
    }

    loadSousCategories(categorieId) {
        const $sousCategorie = $('#familleSousCategorie');

        if (!categorieId) {
            $sousCategorie.html('<option value="">-- Sélectionner une catégorie d\'abord --</option>');
            $sousCategorie.prop('disabled', true);
            return;
        }

        $.ajax({
            url: `/cores/referentiel/familles/sous-categories/${categorieId}`,
            method: 'GET',
            success: (response) => {
                if (response.success) {
                    $sousCategorie.html('<option value="">-- Sélectionner --</option>');
                    response.data.forEach(sc => {
                        $sousCategorie.append(`<option value="${sc.id}">${sc.code} - ${sc.libelle}</option>`);
                    });
                    $sousCategorie.prop('disabled', false);
                }
            },
            error: () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de charger les sous-catégories'
                });
            }
        });
    }

    open(familleData = null) {
        this.$form[0].reset();
        $('#familleSousCategorie').html('<option value="">-- Sélectionner une catégorie d\'abord --</option>').prop('disabled', true);

        if (familleData) {
            // Edit mode
            $('#familleModalLabel').text('Modifier la famille');
            $('#familleId').val(familleData.id);
            $('#familleCode').val(familleData.code);
            $('#familleLibelle').val(familleData.libelle);
            $('#familleDuree').val(familleData.duree_amortissement);

            // Load category and subcategory
            if (familleData.sous_categorie && familleData.sous_categorie.categorie_id) {
                $('#familleCategorie').val(familleData.sous_categorie.categorie_id);
                this.loadSousCategories(familleData.sous_categorie.categorie_id);

                // Set subcategory after loading
                setTimeout(() => {
                    $('#familleSousCategorie').val(familleData.sous_categorie_id);
                }, 300);
            }
        } else {
            // Add mode
            $('#familleModalLabel').text('Nouvelle famille');
            $('#familleId').val('');
        }

        this.$modal.modal('show');
    }

    handleSubmit(e) {
        e.preventDefault();

        const id = $('#familleId').val();
        const url = id
            ? `/cores/referentiel/familles/${id}`
            : '/cores/referentiel/familles';
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
