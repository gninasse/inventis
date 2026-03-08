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
        Schema::create('referentiel_budgets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->year('exercice')->unique();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->decimal('montant_initial', 15, 2);
            $table->decimal('montant_engage', 15, 2)->default(0);
            $table->decimal('montant_disponible', 15, 2);
            $table->enum('statut', ['previsionnel', 'en_cours', 'cloture'])->default('previsionnel');
            $table->foreignId('responsable_id')->nullable()->constrained('users');
            $table->text('observations')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_budgets');
    }
};
