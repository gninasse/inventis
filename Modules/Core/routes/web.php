<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\ActivityController;
use Modules\Core\Http\Controllers\AuthController;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\ModuleController;
use Modules\Core\Http\Controllers\PermissionController;
use Modules\Core\Http\Controllers\ProfileController;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\UserController;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('cores')->name('cores.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Routes pour la gestion des utilisateurs
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/data', [UserController::class, 'getData'])->name('data');
            Route::get('/{id}', [UserController::class, 'show'])->name('show');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
            Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::put('/{id}/profile', [UserController::class, 'updateProfile'])->name('update-profile');
            Route::post('/{id}/avatar', [UserController::class, 'updateAvatar'])->name('update-avatar');

            // Gestion des rôles via AJAX sur la page show
            Route::get('/{id}/roles/available', [UserController::class, 'getAvailableRoles'])->name('available-roles');
            Route::post('/{id}/roles', [UserController::class, 'assignRole'])->name('assign-role');
            Route::delete('/{id}/roles', [UserController::class, 'removeRole'])->name('remove-role');
            Route::delete('/{id}/permissions', [UserController::class, 'removePermission'])->name('remove-permission');
            Route::get('/{id}/permissions/available', [UserController::class, 'getAvailablePermissions'])->name('available-permissions');
            Route::post('/{id}/permissions', [UserController::class, 'assignPermissions'])->name('assign-permissions');
        });

        // Routes pour la gestion des rôles
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/data', [RoleController::class, 'getData'])->name('data');
            Route::get('/{id}', [RoleController::class, 'show'])->name('show');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::put('/{id}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');

            // Gestion des permissions du rôle
            Route::get('/{id}/permissions', [RoleController::class, 'getPermissions'])->name('permissions');
            Route::post('/{id}/toggle-permission', [RoleController::class, 'togglePermission'])->name('toggle-permission');
        });

        // Routes pour la gestion des permissions
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::post('/toggle', [PermissionController::class, 'toggle'])->name('toggle');
            Route::post('/sync', [ModuleController::class, 'syncPermissions'])->name('sync');
        });

        // Routes pour la gestion des modules
        Route::prefix('modules')->name('modules.')->group(function () {
            Route::get('/', [ModuleController::class, 'index'])->name('index');
            Route::post('/install', [ModuleController::class, 'install'])->name('install');
            Route::get('/{slug}', [ModuleController::class, 'show'])->name('show');
            Route::post('/{slug}/enable', [ModuleController::class, 'enable'])->name('enable');
            Route::post('/{slug}/disable', [ModuleController::class, 'disable'])->name('disable');
            Route::delete('/{slug}', [ModuleController::class, 'uninstall'])->name('uninstall');
            Route::get('/{slug}/configure', [ModuleController::class, 'configure'])->name('configure');
            Route::post('/{slug}/configure', [ModuleController::class, 'updateConfiguration'])->name('configure.update');
        });

        // Routes pour la gestion des activités
        Route::prefix('activities')->name('activities.')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/data', [ActivityController::class, 'getData'])->name('data');
            Route::get('/{id}', [ActivityController::class, 'show'])->name('show');
        });

        // Routes pour le profil utilisateur
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        // --- REFERENTIEL ---
        Route::prefix('referentiel')->name('referentiel.')->group(function () {

            // Categories
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\CategorieController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\CategorieController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\CategorieController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\CategorieController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\CategorieController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\CategorieController::class, 'destroy'])->name('destroy');
            });

            // Sous-Categories
            Route::prefix('sous-categories')->name('sous-categories.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\SousCategorieController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\SousCategorieController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\SousCategorieController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\SousCategorieController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\SousCategorieController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\SousCategorieController::class, 'destroy'])->name('destroy');
            });

            // Familles
            Route::prefix('familles')->name('familles.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'show'])->name('show');
                Route::get('/sous-categories/{categorieId}', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'getSousCategories'])->name('sous-categories-by-categorie');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FamilleController::class, 'destroy'])->name('destroy');
            });

            // Articles
            Route::prefix('articles')->name('articles.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'getData'])->name('data');
                Route::get('categories', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'getCategories'])->name('categories');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'destroy'])->name('destroy');
                Route::get('sous-categories/{categorieId}', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'getSousCategories'])->name('sous-categories');
                Route::get('familles/{sousCategorieId}', [\Modules\Core\Http\Controllers\Referentiel\ArticleController::class, 'getFamilles'])->name('familles');
            });

            // Unités de mesure
            Route::prefix('unites')->name('unites.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\UniteMesureController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\UniteMesureController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\UniteMesureController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\UniteMesureController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\UniteMesureController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\UniteMesureController::class, 'destroy'])->name('destroy');
            });

            // Fournisseurs
            Route::prefix('fournisseurs')->name('fournisseurs.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\FournisseurController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\FournisseurController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FournisseurController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\FournisseurController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FournisseurController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FournisseurController::class, 'destroy'])->name('destroy');
            });

            // Fabricants
            Route::prefix('fabricants')->name('fabricants.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'destroy'])->name('destroy');

                // Marques (Nested or custom endpoints)
                Route::post('/marques', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'storeMarque'])->name('marques.store');
                Route::delete('/marques/{id}', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'destroyMarque'])->name('marques.destroy');

                // Modeles
                Route::post('/modeles', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'storeModele'])->name('modeles.store');
                Route::delete('/modeles/{id}', [\Modules\Core\Http\Controllers\Referentiel\FabricantController::class, 'destroyModele'])->name('modeles.destroy');
            });

            // Sources de financement
            Route::prefix('sources')->name('sources.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\SourceFinancementController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\SourceFinancementController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\SourceFinancementController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\SourceFinancementController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\SourceFinancementController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\SourceFinancementController::class, 'destroy'])->name('destroy');
            });

            // Modes d'acquisition
            Route::prefix('modes-acquisition')->name('modes-acquisition.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\ModeAcquisitionController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\ModeAcquisitionController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\ModeAcquisitionController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\ModeAcquisitionController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\ModeAcquisitionController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\ModeAcquisitionController::class, 'destroy'])->name('destroy');
            });

            // Budgets
            Route::prefix('budgets')->name('budgets.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\BudgetController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\BudgetController::class, 'getData'])->name('data');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\BudgetController::class, 'show'])->name('show');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\BudgetController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\BudgetController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\BudgetController::class, 'destroy'])->name('destroy');
            });

            // Magasins
            Route::prefix('magasins')->name('magasins.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'getData'])->name('data');
                Route::post('/', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'store'])->name('store');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'destroy'])->name('destroy');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'show'])->name('show');

                // Emplacements
                Route::get('/{magasinId}/emplacements/data', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'getEmplacementsData'])->name('emplacements.data');
                Route::post('/emplacements', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'storeEmplacement'])->name('emplacements.store');
                Route::delete('/emplacements/{id}', [\Modules\Core\Http\Controllers\Referentiel\MagasinController::class, 'destroyEmplacement'])->name('emplacements.destroy');
            });
        });

        // --- ORGANISATION ---
        Route::prefix('organisation')->name('organisation.')->group(function () {
            // Sites
            Route::prefix('sites')->name('sites.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'getData'])->name('data');
                Route::get('/arborescence', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'getArborescence'])->name('arborescence');
                Route::post('/', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'show'])->name('show');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Organisation\SiteController::class, 'destroy'])->name('destroy');
            });

            // Directions
            Route::prefix('directions')->name('directions.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Organisation\DirectionController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Organisation\DirectionController::class, 'getData'])->name('data');
                Route::post('/', [\Modules\Core\Http\Controllers\Organisation\DirectionController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Organisation\DirectionController::class, 'show'])->name('show');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Organisation\DirectionController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Organisation\DirectionController::class, 'destroy'])->name('destroy');
            });

            // Services
            Route::prefix('services')->name('services.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'getData'])->name('data');
                Route::get('/directions-by-site/{siteId}', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'getDirectionsBySite'])->name('directions-by-site'); // Helper ajax
                Route::post('/', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'show'])->name('show');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Organisation\ServiceController::class, 'destroy'])->name('destroy');
            });

            // Unités
            Route::prefix('unites')->name('unites.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'getData'])->name('data');
                Route::get('/majors', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'getMajors'])->name('majors');
                Route::get('/services-by-direction/{directionId}', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'getServicesByDirection'])->name('services-by-direction'); // Helper ajax
                Route::post('/', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'show'])->name('show');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Organisation\UniteController::class, 'destroy'])->name('destroy');
            });
        });

        // --- PATRIMOINE (Référentiels) ---
        Route::prefix('patrimoine')->name('patrimoine.')->group(function () {
            // Statuts
            Route::prefix('statuts')->name('statuts.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'getData'])->name('data');
                Route::post('/', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'show'])->name('show');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [\Modules\Core\Http\Controllers\Patrimoine\StatutBienController::class, 'reorder'])->name('reorder');
            });

            // États
            Route::prefix('etats')->name('etats.')->group(function () {
                Route::get('/', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'index'])->name('index');
                Route::get('/data', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'getData'])->name('data');
                Route::post('/', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'store'])->name('store');
                Route::get('/{id}', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'show'])->name('show');
                Route::put('/{id}', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'update'])->name('update');
                Route::delete('/{id}', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [\Modules\Core\Http\Controllers\Patrimoine\EtatBienController::class, 'reorder'])->name('reorder');
            });
        });
    });
});
Route::resource('cores', CoreController::class)->names('core');
