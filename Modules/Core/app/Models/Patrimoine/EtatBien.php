<?php

namespace Modules\Core\Models\Patrimoine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtatBien extends Model
{
    use HasFactory;

    protected $table = 'patrimoine_etats_bien';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'couleur',
        'icone',
        'declencheur_reforme',
        'actif',
        'ordre',
        'is_system',
    ];

    protected $casts = [
        'declencheur_reforme' => 'boolean',
        'actif' => 'boolean',
        'is_system' => 'boolean',
        'ordre' => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public static function getByCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }

    public function getBadgeHtmlAttribute(): string
    {
        $iconeHtml = $this->icone ? "<i class='{$this->icone} me-1'></i>" : '';

        return "<span class='badge' style='background-color: {$this->couleur}; color: #fff;'>{$iconeHtml} {$this->libelle}</span>";
    }
}
