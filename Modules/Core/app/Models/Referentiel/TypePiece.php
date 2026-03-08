<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePiece extends Model
{
    use HasFactory;

    protected $table = 'referentiel_types_pieces';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'formats_acceptes',
        'taille_max_mo',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'formats_acceptes' => 'array',
        'taille_max_mo' => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
