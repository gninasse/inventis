<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrimoine_etats_bien', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('couleur', 7)->default('#6c757d');
            $table->string('icone')->nullable();
            $table->boolean('declencheur_reforme')->default(false)->comment('true = suggère d\'ouvrir une procédure de réforme');
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrimoine_etats_bien');
    }
};
