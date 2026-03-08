<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emplacement extends Model
{
    use HasFactory;

    protected $table = 'referentiel_emplacements';

    protected $fillable = [
        'magasin_id',
        'code',
        'libelle',
        'capacite_max',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'capacite_max' => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function magasin()
    {
        return $this->belongsTo(Magasin::class, 'magasin_id');
    }
}
