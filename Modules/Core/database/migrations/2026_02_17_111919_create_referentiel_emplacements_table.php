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
        Schema::create('referentiel_emplacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magasin_id')->constrained('referentiel_magasins')->onDelete('cascade');
            $table->string('code');
            $table->string('libelle');
            $table->integer('capacite_max')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['magasin_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_emplacements');
    }
};
