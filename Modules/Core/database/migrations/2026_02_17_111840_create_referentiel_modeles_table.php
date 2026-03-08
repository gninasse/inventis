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
        Schema::create('referentiel_modeles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marque_id')->constrained('referentiel_marques')->onDelete('cascade');
            $table->string('reference');
            $table->string('designation');
            $table->foreignId('famille_id')->nullable()->constrained('referentiel_familles');
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->unique(['marque_id', 'reference']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_modeles');
    }
};
