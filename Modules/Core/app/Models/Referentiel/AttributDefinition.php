<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributDefinition extends Model
{
    use HasFactory;

    protected $table = 'referentiel_attribut_definitions';

    protected $fillable = [
        'famille_id',
        'nom',
        'type',
        'obligatoire',
        'unite',
        'options',
        'ordre',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'obligatoire' => 'boolean',
        'options' => 'array',
        'ordre' => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function famille()
    {
        return $this->belongsTo(Famille::class, 'famille_id');
    }
}
