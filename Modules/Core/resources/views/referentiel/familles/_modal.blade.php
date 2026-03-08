<div class="modal fade" id="familleModal" tabindex="-1" aria-labelledby="familleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="familleModalLabel">Nouvelle famille</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="familleForm">
                @csrf
                <input type="hidden" id="familleId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="familleCategorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select" id="familleCategorie" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->code }} - {{ $categorie->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="familleSousCategorie" class="form-label">Sous-catégorie <span class="text-danger">*</span></label>
                        <select class="form-select" id="familleSousCategorie" name="sous_categorie_id" required disabled>
                            <option value="">-- Sélectionner une catégorie d'abord --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="familleCode" class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="familleCode" name="code" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="familleLibelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="familleLibelle" name="libelle" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="familleDuree" class="form-label">Durée d'amortissement (ans) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="familleDuree" name="duree_amortissement" required min="0" max="100">
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
