<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Nouveau Fournisseur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="fournisseurForm">
                    <input type="hidden" id="fournisseur_id" name="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="raison_sociale" class="form-label">Raison Sociale <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="raison_sociale" name="raison_sociale" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sigle" class="form-label">Sigle</label>
                            <input type="text" class="form-control" id="sigle" name="sigle">
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="commercial">Commercial</option>
                                <option value="donateur">Donateur</option>
                                <option value="institution">Institution</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pays" class="form-label">Pays <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pays" name="pays" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse" name="adresse" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ifu" class="form-label">IFU</label>
                            <input type="text" class="form-control" id="ifu" name="ifu">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rccm" class="form-label">RCCM</label>
                            <input type="text" class="form-control" id="rccm" name="rccm">
                        </div>
                    </div>

                    <h6 class="mt-3 text-primary">Contact Principal</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact_nom" class="form-label">Nom du Contact</label>
                            <input type="text" class="form-control" id="contact_nom" name="contact_nom">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact_telephone" class="form-label">Tél. du Contact</label>
                            <input type="text" class="form-control" id="contact_telephone" name="contact_telephone">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btn-save-fournisseur"><i class="fas fa-save"></i> Enregistrer</button>
            </div>
        </div>
    </div>
</div>
