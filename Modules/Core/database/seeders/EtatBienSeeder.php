<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Patrimoine\EtatBien;

class EtatBienSeeder extends Seeder
{
    public function run(): void
    {
        $etats = [
            [
                'code' => 'BON',
                'libelle' => 'Bon état',
                'couleur' => '#28a745',
                'declencheur_reforme' => false,
                'is_system' => true,
                'ordre' => 10,
            ],
            [
                'code' => 'PASSABLE',
                'libelle' => 'État passable',
                'couleur' => '#ffc107',
                'declencheur_reforme' => false,
                'is_system' => true,
                'ordre' => 20,
            ],
            [
                'code' => 'MAUVAIS',
                'libelle' => 'Mauvais état',
                'couleur' => '#fd7e14',
                'declencheur_reforme' => true,
                'is_system' => true,
                'ordre' => 30,
            ],
            [
                'code' => 'AVARIE',
                'libelle' => 'Avarié / Altéré',
                'couleur' => '#dc3545',
                'declencheur_reforme' => true,
                'is_system' => true,
                'ordre' => 40,
            ],
        ];

        foreach ($etats as $etat) {
            EtatBien::firstOrCreate(
                ['code' => $etat['code']],
                $etat
            );
        }
    }
}
