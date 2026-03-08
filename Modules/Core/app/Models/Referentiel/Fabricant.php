<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricant extends Model
{
    use HasFactory;

    protected $table = 'referentiel_fabricants';

    protected $fillable = [
        'raison_sociale',
        'pays',
        'site_web',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function marques()
    {
        return $this->hasMany(Marque::class, 'fabricant_id');
    }
}
