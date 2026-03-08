<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeAcquisition extends Model
{
    use HasFactory;

    protected $table = 'referentiel_modes_acquisition';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'pieces_requises',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'pieces_requises' => 'array',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
