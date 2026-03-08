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
        Schema::create('referentiel_marques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fabricant_id')->constrained('referentiel_fabricants')->onDelete('cascade');
            $table->string('nom');
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['fabricant_id', 'nom']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_marques');
    }
};
