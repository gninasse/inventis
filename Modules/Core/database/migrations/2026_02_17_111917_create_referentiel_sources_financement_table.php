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
        Schema::create('referentiel_sources_financement', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->enum('type', ['budget_etat', 'budget_propre', 'subvention', 'don', 'pret', 'cooperation']);
            $table->string('organisme')->nullable();
            $table->string('reference_convention')->nullable();
            $table->year('exercice_debut')->nullable();
            $table->year('exercice_fin')->nullable();
            $table->decimal('montant_alloue', 15, 2)->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_sources_financement');
    }
};
