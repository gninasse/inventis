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
        Schema::create('referentiel_types_pieces', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->json('formats_acceptes')->nullable();
            $table->integer('taille_max_mo')->default(10);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // Seeder immédiat
        DB::table('referentiel_types_pieces')->insert([
            ['code' => 'FAC', 'libelle' => 'Facture', 'formats_acceptes' => json_encode(['pdf', 'jpg']), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BDL', 'libelle' => 'Bon de livraison', 'formats_acceptes' => json_encode(['pdf', 'jpg']), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'PVR', 'libelle' => 'Procès-verbal de réception', 'formats_acceptes' => json_encode(['pdf']), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ATD', 'libelle' => 'Acte de donation', 'formats_acceptes' => json_encode(['pdf']), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CTR', 'libelle' => 'Contrat', 'formats_acceptes' => json_encode(['pdf']), 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BDC', 'libelle' => 'Bon de commande', 'formats_acceptes' => json_encode(['pdf']), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_types_pieces');
    }
};
