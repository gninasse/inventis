<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    use HasFactory;

    protected $table = 'referentiel_sous_categories';

    protected $fillable = [
        'categorie_id',
        'code',
        'libelle',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function familles()
    {
        return $this->hasMany(Famille::class, 'sous_categorie_id');
    }
}
