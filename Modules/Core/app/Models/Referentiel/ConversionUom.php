<?php

namespace Modules\Core\Models\Referentiel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionUom extends Model
{
    use HasFactory;

    protected $table = 'referentiel_conversions_uom';

    protected $fillable = [
        'article_id',
        'unite_source_id',
        'unite_cible_id',
        'facteur_conversion',
    ];

    protected $casts = [
        'facteur_conversion' => 'decimal:6',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function uniteSource()
    {
        return $this->belongsTo(UniteMesure::class, 'unite_source_id');
    }

    public function uniteCible()
    {
        return $this->belongsTo(UniteMesure::class, 'unite_cible_id');
    }
}
