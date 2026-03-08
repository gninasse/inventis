<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nouvelle Unité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" id="itemId" name="id">
                    
                    <div class="mb-3">
                        <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="libelle" name="libelle" required>
                    </div>

                    <div class="mb-3">
                        <label for="abreviation" class="form-label">Abréviation <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="abreviation" name="abreviation" required>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="quantite">Quantité</option>
                            <option value="masse">Masse</option>
                            <option value="volume">Volume</option>
                            <option value="longueur">Longueur</option>
                        </select>
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
