<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Models\User;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'referentiel_budgets';

    protected $fillable = [
        'code',
        'libelle',
        'exercice',
        'date_debut',
        'date_fin',
        'montant_initial',
        'montant_engage',
        'montant_disponible',
        'statut',
        'responsable_id',
        'observations',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'exercice' => 'integer',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant_initial' => 'decimal:2',
        'montant_engage' => 'decimal:2',
        'montant_disponible' => 'decimal:2',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public static function getBudgetEnCours()
    {
        return static::where('statut', 'en_cours')->where('actif', true)->first();
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
