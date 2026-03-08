<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{
    use HasFactory;

    protected $table = 'referentiel_familles';

    protected $fillable = [
        'sous_categorie_id',
        'code',
        'libelle',
        'duree_amortissement',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'duree_amortissement' => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class, 'sous_categorie_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'famille_id');
    }

    public function attributDefinitions()
    {
        return $this->hasMany(AttributDefinition::class, 'famille_id');
    }
}
