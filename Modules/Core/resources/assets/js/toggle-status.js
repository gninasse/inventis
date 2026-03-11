/**
 * Gestion de l'activation/désactivation des éléments
 */
window.ToggleStatusManager = {
    /**
     * Initialiser les boutons toggle-status
     * @param {string} routeName - Nom de la route (ex: 'cores.referentiel.articles.toggle-status')
     * @param {string} tableId - ID de la table Bootstrap Table
     */
    init(routeName, tableId = '#table') {
        $(document).on('click', '.btn-toggle-status', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const actif = $(this).data('actif');
            const action = actif ? 'désactiver' : 'activer';
            
            Swal.fire({
                title: `Confirmer l'action`,
                text: `Voulez-vous vraiment ${action} cet élément ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Oui, confirmer',
                cancelButtonText: 'Annuler',
                confirmButtonColor: actif ? '#dc3545' : '#28a745',
            }).then((result) => {
                if (result.isConfirmed) {
                    ToggleStatusManager.toggle(id, routeName, tableId);
                }
            });
        });
    },

    /**
     * Basculer le statut d'un élément
     */
    toggle(id, routeName, tableId) {
        $.ajax({
            url: route(routeName, id),
            type: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $(tableId).bootstrapTable('refresh');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: xhr.responseJSON?.message || 'Une erreur est survenue'
                });
            }
        });
    },

    /**
     * Formateur de colonne pour les boutons d'action avec toggle
     */
    actionFormatter(value, row, index, canUpdate, canDestroy, canToggle, editRoute, deleteRoute, toggleRoute) {
        let actions = '';
        
        // Bouton Activer/Désactiver
        if (canToggle) {
            const statusClass = row.actif ? 'btn-warning' : 'btn-success';
            const statusIcon = row.actif ? 'fa-ban' : 'fa-check';
            const statusTitle = row.actif ? 'Désactiver' : 'Activer';
            actions += `<button class="btn ${statusClass} btn-sm btn-toggle-status me-1" 
                               data-id="${row.id}" 
                               data-actif="${row.actif ? 1 : 0}"
                               title="${statusTitle}">
                            <i class="fas ${statusIcon}"></i>
                        </button>`;
        }
        
        // Bouton Modifier
        if (canUpdate) {
            actions += `<button class="btn btn-primary btn-sm btn-edit me-1" 
                               data-id="${row.id}" 
                               title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>`;
        }
        
        // Bouton Supprimer
        if (canDestroy) {
            actions += `<button class="btn btn-danger btn-sm btn-delete" 
                               data-id="${row.id}" 
                               title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>`;
        }
        
        return actions || '<span class="text-muted">Aucune action</span>';
    },

    /**
     * Formateur de statut avec badge
     */
    statusFormatter(value, row) {
        if (row.actif) {
            return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Actif</span>';
        }
        return '<span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Inactif</span>';
    }
};
