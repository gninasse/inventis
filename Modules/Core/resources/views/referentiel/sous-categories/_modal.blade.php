<div class="modal fade" id="sousCategorieModal" tabindex="-1" aria-labelledby="sousCategorieModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sousCategorieModalLabel">Nouvelle sous-catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sousCategorieForm">
                @csrf
                <input type="hidden" id="sousCategorieId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sousCategorieCategorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                        <select class="form-select" id="sousCategorieCategorie" name="categorie_id" required>
                            <option value="">-- Sélectionner --</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->code }} - {{ $categorie->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sousCategorieCode" class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sousCategorieCode" name="code" required maxlength="50">
                    </div>
                    <div class="mb-3">
                        <label for="sousCategorieLibelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sousCategorieLibelle" name="libelle" required maxlength="255">
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
