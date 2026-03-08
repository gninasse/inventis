<div class="modal fade" id="createStatutModal" tabindex="-1" aria-labelledby="createStatutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStatutModalLabel">Nouveau Statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statutForm">
                @csrf
                <input type="hidden" id="statut_id" name="id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" required>
                            <small class="text-muted">Sera mis en majuscules automatiquement.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aperçu</label>
                            <div id="badge-preview" class="p-2 border rounded text-center">
                                <span class="badge" style="background-color: #6c757d;">Aperçu</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="libelle" name="libelle" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="couleur" class="form-label">Couleur</label>
                            <input type="color" class="form-control form-control-color w-100" id="couleur" name="couleur" value="#6c757d">
                        </div>
                        <div class="col-md-6">
                            <label for="icone" class="form-label">Icône (Bootstrap Icons)</label>
                            <input type="text" class="form-control" id="icone" name="icone" placeholder="ex: fas fa-box">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="impact_comptable" name="impact_comptable">
                        <label class="form-check-label" for="impact_comptable">Impact Comptable (Génère écritures)</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="actif" name="actif" checked>
                        <label class="form-check-label" for="actif">Actif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
