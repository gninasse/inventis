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
        Schema::create('referentiel_familles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sous_categorie_id')->constrained('referentiel_sous_categories')->onDelete('cascade');
            $table->string('code');
            $table->string('libelle');
            $table->integer('duree_amortissement')->nullable()->comment('En années');
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['sous_categorie_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_familles');
    }
};
