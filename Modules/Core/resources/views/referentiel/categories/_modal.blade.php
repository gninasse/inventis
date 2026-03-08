<div class="modal fade" id="categorieModal" tabindex="-1" aria-labelledby="categorieModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categorieModalLabel">Nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categorieForm">
                @csrf
                <input type="hidden" id="categorieId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categorieCode" class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categorieCode" name="code" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="categorieLibelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="categorieLibelle" name="libelle" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="categorieDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categorieDescription" name="description" rows="3" maxlength="1000"></textarea>
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
