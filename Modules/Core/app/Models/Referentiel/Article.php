<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'referentiel_articles';

    protected $fillable = [
        'famille_id',
        'code_national',
        'designation',
        'type',
        'description',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    protected $appends = ['famille_path'];

    public function getFamillePathAttribute()
    {
        if ($this->famille && $this->famille->sousCategorie && $this->famille->sousCategorie->categorie) {
            return $this->famille->sousCategorie->categorie->libelle.' > '.$this->famille->sousCategorie->libelle.' > '.$this->famille->libelle;
        }

        return 'N/A';
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function famille()
    {
        return $this->belongsTo(Famille::class, 'famille_id');
    }

    public function conversions()
    {
        return $this->hasMany(ConversionUom::class, 'article_id');
    }
}
