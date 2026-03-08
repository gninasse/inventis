<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;

    protected $table = 'referentiel_marques';

    protected $fillable = [
        'fabricant_id',
        'nom',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function fabricant()
    {
        return $this->belongsTo(Fabricant::class, 'fabricant_id');
    }

    public function modeles()
    {
        return $this->hasMany(Modele::class, 'marque_id');
    }
}
