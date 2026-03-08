<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nouveau Budget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" id="itemId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="libelle" name="libelle" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="exercice" class="form-label">Exercice <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="exercice" name="exercice" required min="2000" max="2100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="date_debut" class="form-label">Date Début <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label for="date_fin" class="form-label">Date Fin <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="montant_initial" class="form-label">Montant Initial <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="montant_initial" name="montant_initial" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select class="form-select" id="statut" name="statut" required>
                                <option value="previsionnel">Prévisionnel</option>
                                <option value="en_cours">En Cours</option>
                                <option value="cloture">Clôturé</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observations" class="form-label">Observations</label>
                        <textarea class="form-control" id="observations" name="observations" rows="2"></textarea>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveItem()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
