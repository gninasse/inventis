<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;

class Magasin extends Model
{
    use HasFactory;

    protected $table = 'referentiel_magasins';

    protected $fillable = [
        'site_id',
        'code',
        'libelle',
        'type',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'site_id' => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function emplacements()
    {
        return $this->hasMany(Emplacement::class, 'magasin_id');
    }
}
