<div class="modal fade" id="emplacementsModal" tabindex="-1" aria-labelledby="emplacementsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emplacementsModalTitle">Gérer les Emplacements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Form -->
                <div class="card mb-3 bg-light">
                    <div class="card-body py-2">
                        <h6 class="card-title">Ajouter un emplacement</h6>
                        <form id="emplacementForm" class="row g-2 align-items-end">
                            <input type="hidden" id="emplacementMagasinId" name="magasin_id">
                            <div class="col-md-3">
                                <label class="form-label small">Code</label>
                                <input type="text" class="form-control form-control-sm" name="code" required placeholder="Ex: R1-E1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Libellé</label>
                                <input type="text" class="form-control form-control-sm" name="libelle" required placeholder="Rayon 1 Etagère 1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Capacité</label>
                                <input type="number" class="form-control form-control-sm" name="capacite_max" placeholder="Max">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-sm btn-success w-100" onclick="saveEmplacement()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List -->
                <div id="emplacementsList" style="max-height: 300px; overflow-y: auto;">
                    <!-- Check JS -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
