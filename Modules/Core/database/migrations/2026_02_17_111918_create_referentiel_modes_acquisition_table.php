<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referentiel_modes_acquisition', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->json('pieces_requises')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // Seeder immédiat
        DB::table('referentiel_modes_acquisition')->insert([
            ['code' => 'CMD', 'libelle' => 'Commande directe', 'pieces_requises' => json_encode([]), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DON', 'libelle' => 'Don', 'pieces_requises' => json_encode(['PVR']), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'LEG', 'libelle' => 'Legs', 'pieces_requises' => json_encode([]), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'TRF', 'libelle' => 'Transfert', 'pieces_requises' => json_encode([]), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PRD', 'libelle' => 'Production interne', 'pieces_requises' => json_encode([]), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_modes_acquisition');
    }
};
