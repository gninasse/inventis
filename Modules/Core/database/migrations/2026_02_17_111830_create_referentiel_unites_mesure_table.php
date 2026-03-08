<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referentiel_unites_mesure', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('abreviation')->unique();
            $table->enum('type', ['masse', 'volume', 'longueur', 'quantite']);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_unites_mesure');
    }
};
