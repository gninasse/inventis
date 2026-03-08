<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;

    protected $table = 'referentiel_modeles';

    protected $fillable = [
        'marque_id',
        'reference',
        'designation',
        'famille_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function marque()
    {
        return $this->belongsTo(Marque::class, 'marque_id');
    }

    public function famille()
    {
        return $this->belongsTo(Famille::class, 'famille_id');
    }
}
