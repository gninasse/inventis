<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'referentiel_categories';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'compte_comptable',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function sousCategories()
    {
        return $this->hasMany(SousCategorie::class, 'categorie_id');
    }
}
