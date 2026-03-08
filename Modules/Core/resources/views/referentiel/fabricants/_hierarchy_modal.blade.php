<div class="modal fade" id="hierarchyModal" tabindex="-1" aria-labelledby="hierarchyModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hierarchyModalTitle">Marques & Modèles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add Marque -->
                <div class="card mb-3 bg-light">
                    <div class="card-body py-2">
                        <h6 class="card-title">Ajouter une Marque</h6>
                        <form id="marqueForm" class="row g-2 align-items-end">
                            <input type="hidden" id="fabricantIdForMarque" name="fabricant_id">
                            <div class="col-md-9">
                                <label class="form-label small">Nom de la Marque</label>
                                <input type="text" class="form-control form-control-sm" id="nomMarque" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-sm btn-success w-100" onclick="saveMarque()">
                                    <i class="fas fa-plus"></i> Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- List Content -->
                <div id="hierarchyContent" style="max-height: 400px; overflow-y: auto;">
                    <!-- JS Loaded -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
