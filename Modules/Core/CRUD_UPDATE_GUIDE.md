# Guide de mise à jour des CRUDs avec activation/désactivation

## Modifications effectuées

### 1. Trait `HasActivationToggle`
Créé dans `Modules/Core/app/Traits/HasActivationToggle.php`

Ce trait ajoute automatiquement la méthode `toggleStatus($id)` à tous les contrôleurs.

### 2. Contrôleurs mis à jour

Tous les contrôleurs suivants ont été modifiés :

**Référentiel:**
- CategorieController
- SousCategorieController
- FamilleController
- ArticleController
- UniteMesureController
- FournisseurController
- FabricantController
- SourceFinancementController
- ModeAcquisitionController
- BudgetController
- MagasinController

**Organisation:**
- SiteController
- BatimentController
- EtageController
- LocalController
- DirectionController
- ServiceController
- UniteController

### 3. Changements dans les contrôleurs

**Avant:**
```php
public function destroy($id)
{
    $item->actif = false;
    $item->save();
    return response()->json(['success' => true, 'message' => 'Élément désactivé']);
}
```

**Après:**
```php
use Modules\Core\app\Traits\HasActivationToggle;

class ExampleController extends Controller
{
    use HasActivationToggle;
    
    protected function getModelClass(): string
    {
        return Example::class;
    }
    
    public function destroy($id)
    {
        $item->delete(); // Suppression physique
        return response()->json(['success' => true, 'message' => 'Élément supprimé']);
    }
    
    // La méthode toggleStatus() est ajoutée automatiquement par le trait
}
```

### 4. Permissions ajoutées

Pour chaque ressource, une nouvelle permission `toggle-status` a été ajoutée :
- `cores.referentiel.articles.toggle-status`
- `cores.referentiel.categories.toggle-status`
- `cores.organisation.sites.toggle-status`
- etc.

### 5. Routes à ajouter

Dans `Modules/Core/routes/web.php`, ajouter pour chaque ressource :

```php
Route::patch('articles/{id}/toggle-status', [ArticleController::class, 'toggleStatus'])
    ->name('cores.referentiel.articles.toggle-status');
```

### 6. Utilisation dans les vues

#### a) Inclure le script JS

Dans votre vue Blade :
```blade
@push('js')
<script src="{{ asset('js/modules/core/toggle-status.js') }}"></script>
<script>
    ToggleStatusManager.init('cores.referentiel.articles.toggle-status', '#table');
</script>
@endpush
```

#### b) Modifier le formateur de colonnes Bootstrap Table

```javascript
{
    field: 'actif',
    title: 'Statut',
    align: 'center',
    formatter: ToggleStatusManager.statusFormatter
},
{
    field: 'actions',
    title: 'Actions',
    align: 'center',
    formatter: function(value, row) {
        return ToggleStatusManager.actionFormatter(
            value, row, null,
            {{ auth()->user()->can('cores.referentiel.articles.update') ? 'true' : 'false' }},
            {{ auth()->user()->can('cores.referentiel.articles.destroy') ? 'true' : 'false' }},
            {{ auth()->user()->can('cores.referentiel.articles.toggle-status') ? 'true' : 'false' }}
        );
    }
}
```

### 7. Synchroniser les permissions

Après avoir mis à jour le fichier `config/permissions.php`, exécuter :

```bash
php artisan core:sync-permissions
```

## Exemple complet : ArticleController

Le contrôleur `ArticleController` a été entièrement mis à jour comme exemple de référence.

## Avantages

1. **Suppression physique** : Les éléments sont vraiment supprimés de la base de données
2. **Activation/Désactivation** : Nouvelle fonctionnalité pour masquer temporairement des éléments
3. **Code réutilisable** : Le trait et le JS peuvent être utilisés partout
4. **Permissions granulaires** : Contrôle fin des actions possibles par rôle

## Migration des données

Si vous aviez des éléments avec `actif = false`, ils sont toujours en base. Vous pouvez :
- Les réactiver avec le nouveau bouton
- Les supprimer définitivement avec le bouton supprimer
