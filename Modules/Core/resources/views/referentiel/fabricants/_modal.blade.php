<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nouveau Fabricant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <input type="hidden" id="itemId" name="id">
                    
                    <div class="mb-3">
                        <label for="raison_sociale" class="form-label">Raison Sociale <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="raison_sociale" name="raison_sociale" required>
                    </div>

                    <div class="mb-3">
                        <label for="pays" class="form-label">Pays</label>
                        <input type="text" class="form-control" id="pays" name="pays">
                    </div>

                    <div class="mb-3">
                        <label for="site_web" class="form-label">Site Web</label>
                        <input type="url" class="form-control" id="site_web" name="site_web">
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
