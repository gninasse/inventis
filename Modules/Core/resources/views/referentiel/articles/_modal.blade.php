<div class="modal fade" id="articleModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouvel Article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="articleForm">
                    <input type="hidden" id="article_id" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code_national" class="form-label">Code National <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="code_national" name="code_national" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="designation" class="form-label">Désignation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="designation" name="designation" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" selected disabled>Sélectionner un type</option>
                                    <option value="durable">Durable</option>
                                    <option value="consommable">Consommable</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted">Classification</p>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="categorie_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="categorie_id" name="categorie_id" required>
                                    <option value="">Sélectionner une catégorie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sous_categorie_id" class="form-label">Sous-Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="sous_categorie_id" name="sous_categorie_id" required disabled>
                                    <option value="">Sélectionner une sous-catégorie</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="famille_id" class="form-label">Famille <span class="text-danger">*</span></label>
                                <select class="form-select" id="famille_id" name="famille_id" required disabled>
                                    <option value="">Sélectionner une famille</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btn-save"><i class="fas fa-save"></i> Enregistrer</button>
            </div>
        </div>
    </div>
</div>
