# ✅ Refactorisation Toggle-Status - Inspiré de RoleController

## Ce qui a été fait

### 1. **Contrôleurs mis à jour** (18 contrôleurs)

Chaque contrôleur a maintenant :
- ✅ `destroy()` fait une suppression physique (`delete()`)
- ✅ `toggleStatus($id)` méthode ajoutée directement dans le contrôleur

**Exemple ArticleController :**
```php
public function toggleStatus($id)
{
    try {
        $item = Article::findOrFail($id);
        $item->actif = !$item->actif;
        $item->save();

        return response()->json([
            'success' => true,
            'message' => $item->actif ? 'Élément activé avec succès' : 'Élément désactivé avec succès',
            'actif' => $item->actif,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du changement de statut',
        ], 500);
    }
}
```

### 2. **Routes ajoutées** (18 routes PATCH)

Dans `Modules/Core/routes/web.php` :
```php
Route::patch('referentiel/articles/{id}/toggle-status', [ArticleController::class, 'toggleStatus'])
    ->name('referentiel.articles.toggle-status');
// ... etc pour toutes les ressources
```

### 3. **Permissions ajoutées** (21 permissions)

Dans `Modules/Core/config/permissions.php` :
- `cores.referentiel.articles.toggle-status`
- `cores.referentiel.categories.toggle-status`
- `cores.organisation.sites.toggle-status`
- etc.

### 4. **Vue Articles mise à jour** (exemple complet)

**Bouton ajouté :**
```blade
@can('cores.referentiel.articles.toggle-status')
<button id="btn-toggle-status" class="btn btn-warning" disabled>
    <i class="fas fa-power-off"></i>
</button>
@endcan
```

**Colonne Statut ajoutée :**
```html
<th data-field="actif" data-formatter="statusFormatter" data-align="center">Statut</th>
```

### 5. **JS Articles mis à jour** (exemple complet)

**Formatter ajouté :**
```javascript
window.statusFormatter = function(value, row) {
    if (row.actif) {
        return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Actif</span>';
    }
    return '<span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Inactif</span>';
};
```

**Handler toggle-status ajouté :**
```javascript
$('#btn-toggle-status').on('click', function() {
    const id = tableInstance.getSelectedId();
    if (!id) return;

    const selections = $table.bootstrapTable('getSelections');
    const actif = selections[0].actif;
    const action = actif ? 'désactiver' : 'activer';

    Swal.fire({
        title: 'Confirmer l\'action',
        text: `Voulez-vous vraiment ${action} cet article ?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#ffc107',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: route('cores.referentiel.articles.toggle-status', id),
                type: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tableInstance.refresh();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: xhr.responseJSON?.message || 'Une erreur est survenue'
                    });
                }
            });
        }
    });
});
```

## À faire pour les autres vues

Pour chaque vue (categories, familles, sites, etc.), répliquer le pattern :

1. **Vue Blade** : Ajouter le bouton toggle-status et la colonne actif
2. **Fichier JS** : Ajouter statusFormatter et le handler toggle-status
3. **Tester** : Vérifier que ça fonctionne

## Pattern à suivre

Le code est **identique** pour toutes les fonctionnalités, il suffit de changer :
- Le nom de la route : `cores.referentiel.articles.toggle-status` → `cores.referentiel.categories.toggle-status`
- Le texte : "article" → "catégorie"
- L'ID de la table : `#articles-table` → `#categories-table`

## Commandes à exécuter

```bash
# Synchroniser les permissions
php artisan core:sync-permissions

# Vider le cache
php artisan optimize:clear
```

## Statut

✅ **Articles** - Complet (contrôleur + route + permission + vue + JS)
⚠️ **Autres vues** - Contrôleur + route + permission OK, vue et JS à mettre à jour

Le pattern est établi, il suffit de le répliquer !
