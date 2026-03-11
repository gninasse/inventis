<?php

/**
 * Routes à ajouter dans Modules/Core/routes/web.php
 * pour activer la fonctionnalité toggle-status
 */

// RÉFÉRENTIEL - Toggle Status Routes

// Classification
Route::patch('referentiel/categories/{id}/toggle-status', [CategorieController::class, 'toggleStatus'])
    ->name('cores.referentiel.categories.toggle-status');
Route::patch('referentiel/sous-categories/{id}/toggle-status', [SousCategorieController::class, 'toggleStatus'])
    ->name('cores.referentiel.sous-categories.toggle-status');
Route::patch('referentiel/familles/{id}/toggle-status', [FamilleController::class, 'toggleStatus'])
    ->name('cores.referentiel.familles.toggle-status');

// Articles
Route::patch('referentiel/articles/{id}/toggle-status', [ArticleController::class, 'toggleStatus'])
    ->name('cores.referentiel.articles.toggle-status');

// Unités de mesure
Route::patch('referentiel/unites-mesure/{id}/toggle-status', [UniteMesureController::class, 'toggleStatus'])
    ->name('cores.referentiel.unites.toggle-status');

// Fournisseurs
Route::patch('referentiel/fournisseurs/{id}/toggle-status', [FournisseurController::class, 'toggleStatus'])
    ->name('cores.referentiel.fournisseurs.toggle-status');

// Fabricants
Route::patch('referentiel/fabricants/{id}/toggle-status', [FabricantController::class, 'toggleStatus'])
    ->name('cores.referentiel.fabricants.toggle-status');

// Sources de financement
Route::patch('referentiel/sources-financement/{id}/toggle-status', [SourceFinancementController::class, 'toggleStatus'])
    ->name('cores.referentiel.sources.toggle-status');

// Modes d'acquisition
Route::patch('referentiel/modes-acquisition/{id}/toggle-status', [ModeAcquisitionController::class, 'toggleStatus'])
    ->name('cores.referentiel.modes-acquisition.toggle-status');

// Budgets
Route::patch('referentiel/budgets/{id}/toggle-status', [BudgetController::class, 'toggleStatus'])
    ->name('cores.referentiel.budgets.toggle-status');

// Magasins
Route::patch('referentiel/magasins/{id}/toggle-status', [MagasinController::class, 'toggleStatus'])
    ->name('cores.referentiel.magasins.toggle-status');

// ORGANISATION - Toggle Status Routes

// Sites
Route::patch('organisation/sites/{id}/toggle-status', [SiteController::class, 'toggleStatus'])
    ->name('cores.organisation.sites.toggle-status');

// Bâtiments
Route::patch('organisation/batiments/{id}/toggle-status', [BatimentController::class, 'toggleStatus'])
    ->name('cores.organisation.batiments.toggle-status');

// Étages
Route::patch('organisation/etages/{id}/toggle-status', [EtageController::class, 'toggleStatus'])
    ->name('cores.organisation.etages.toggle-status');

// Locaux
Route::patch('organisation/locaux/{id}/toggle-status', [LocalController::class, 'toggleStatus'])
    ->name('cores.organisation.locaux.toggle-status');

// Directions
Route::patch('organisation/directions/{id}/toggle-status', [DirectionController::class, 'toggleStatus'])
    ->name('cores.organisation.directions.toggle-status');

// Services
Route::patch('organisation/services/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])
    ->name('cores.organisation.services.toggle-status');

// Unités
Route::patch('organisation/unites/{id}/toggle-status', [UniteController::class, 'toggleStatus'])
    ->name('cores.organisation.unites.toggle-status');
