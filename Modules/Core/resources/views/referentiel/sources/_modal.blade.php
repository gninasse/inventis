<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nouvelle Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sourceForm">
                    <input type="hidden" id="source_id" name="id">
                    
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
                         <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="Budget National">Budget National</option>
                                <option value="Bailleur de Fonds">Bailleur de Fonds</option>
                                <option value="Fonds Propres">Fonds Propres</option>
                                <option value="Don">Don</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="organisme" class="form-label">Organisme</label>
                            <input type="text" class="form-control" id="organisme" name="organisme">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reference_convention" class="form-label">Réf. Convention</label>
                        <input type="text" class="form-control" id="reference_convention" name="reference_convention">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="exercice_debut" class="form-label">Exercice Début</label>
                            <input type="number" class="form-control" id="exercice_debut" name="exercice_debut" min="2000" max="2100">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="exercice_fin" class="form-label">Exercice Fin</label>
                            <input type="number" class="form-control" id="exercice_fin" name="exercice_fin" min="2000" max="2100">
                        </div>
                         <div class="col-md-4 mb-3">
                            <label for="montant_alloue" class="form-label">Montant Alloué</label>
                            <input type="number" step="0.01" class="form-control" id="montant_alloue" name="montant_alloue">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btn-save-source"><i class="fas fa-save"></i> Enregistrer</button>
            </div>
        </div>
    </div>
</div>
