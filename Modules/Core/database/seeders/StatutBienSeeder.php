<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Patrimoine\StatutBien;

class StatutBienSeeder extends Seeder
{
    public function run(): void
    {
        $statuts = [
            [
                'code' => 'EN_MAGASIN',
                'libelle' => 'En magasin',
                'couleur' => '#6c757d',
                'impact_comptable' => false,
                'is_system' => true,
                'ordre' => 10,
            ],
            [
                'code' => 'EN_SERVICE',
                'libelle' => 'En service',
                'couleur' => '#28a745',
                'impact_comptable' => false,
                'is_system' => true,
                'ordre' => 20,
            ],
            [
                'code' => 'EN_REPARATION',
                'libelle' => 'En réparation',
                'couleur' => '#fd7e14',
                'impact_comptable' => false,
                'is_system' => true,
                'ordre' => 30,
            ],
            [
                'code' => 'EN_TRANSIT',
                'libelle' => 'En transit',
                'couleur' => '#17a2b8',
                'impact_comptable' => false,
                'is_system' => true,
                'ordre' => 40,
            ],
            [
                'code' => 'PERDU',
                'libelle' => 'Perdu / Disparu',
                'couleur' => '#dc3545',
                'impact_comptable' => true,
                'is_system' => true,
                'ordre' => 50,
            ],
            [
                'code' => 'EN_CONCESSION',
                'libelle' => 'En concession',
                'couleur' => '#6f42c1',
                'impact_comptable' => false,
                'is_system' => true,
                'ordre' => 60,
            ],
            [
                'code' => 'REFORME',
                'libelle' => 'Réformé',
                'couleur' => '#343a40',
                'impact_comptable' => true,
                'is_system' => true,
                'ordre' => 70,
            ],
        ];

        foreach ($statuts as $statut) {
            StatutBien::firstOrCreate(
                ['code' => $statut['code']],
                $statut
            );
        }
    }
}
