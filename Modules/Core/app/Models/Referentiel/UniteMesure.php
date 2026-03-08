<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniteMesure extends Model
{
    use HasFactory;

    protected $table = 'referentiel_unites_mesure';

    protected $fillable = [
        'libelle',
        'abreviation',
        'type',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
