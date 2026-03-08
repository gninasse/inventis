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
        Schema::create('referentiel_sous_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('referentiel_categories')->onDelete('cascade');
            $table->string('code');
            $table->string('libelle');
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['categorie_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_sous_categories');
    }
};
