<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceFinancement extends Model
{
    use HasFactory;

    protected $table = 'referentiel_sources_financement';

    protected $fillable = [
        'code',
        'libelle',
        'type',
        'organisme',
        'reference_convention',
        'exercice_debut',
        'exercice_fin',
        'montant_alloue',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'exercice_debut' => 'integer',
        'exercice_fin' => 'integer',
        'montant_alloue' => 'decimal:2',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
