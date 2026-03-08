<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $table = 'referentiel_fournisseurs';

    protected $fillable = [
        'code',
        'raison_sociale',
        'type',
        'sigle',
        'adresse',
        'telephone',
        'email',
        'ifu',
        'rccm',
        'pays',
        'contact_nom',
        'contact_telephone',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                // Auto-generate code FRN-XXXX
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $model->code = 'FRN-'.str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
