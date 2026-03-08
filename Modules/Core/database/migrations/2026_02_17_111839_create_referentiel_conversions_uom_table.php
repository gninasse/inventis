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
        Schema::create('referentiel_conversions_uom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('referentiel_articles')->onDelete('cascade');
            $table->foreignId('unite_source_id')->constrained('referentiel_unites_mesure')->onDelete('cascade');
            $table->foreignId('unite_cible_id')->constrained('referentiel_unites_mesure')->onDelete('cascade');
            $table->decimal('facteur_conversion', 12, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_conversions_uom');
    }
};
