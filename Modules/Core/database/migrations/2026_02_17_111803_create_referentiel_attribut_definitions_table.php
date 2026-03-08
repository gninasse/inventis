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
        Schema::create('referentiel_attribut_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('famille_id')->constrained('referentiel_familles')->onDelete('cascade');
            $table->string('nom');
            $table->enum('type', ['texte', 'nombre', 'liste', 'booleen']);
            $table->boolean('obligatoire')->default(false);
            $table->string('unite')->nullable();
            $table->json('options')->nullable();
            $table->integer('ordre')->default(0);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_attribut_definitions');
    }
};
