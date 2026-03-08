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
        Schema::create('referentiel_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('famille_id')->constrained('referentiel_familles');
            $table->string('code_national')->unique();
            $table->string('designation');
            $table->enum('type', ['durable', 'consommable']);
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_articles');
    }
};
