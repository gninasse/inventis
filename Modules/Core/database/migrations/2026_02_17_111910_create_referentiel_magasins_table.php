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
        Schema::create('referentiel_magasins', function (Blueprint $table) {
            $table->id();
            // Assuming 'sites' table might not exist yet in Core or elsewhere,
            // but the request said "site_id (nullable, FK→sites si existe)".
            // Since 'sites' is part of 'Organisation' group which currently shows up in sidebar
            // but we haven't created the table 'sites' yet in this run.
            // The user request didn't ask to create 'sites' table in the MIGRATIONS list.
            // It asked to create 'Organisation' group in sidebar, but only detailed tables for 'Referentiel'.
            // Wait, Group 2 "ORGANISATION" has "Sites" link. But the user didn't ask to create migration for Sites here?
            // "MIGRATIONS À CRÉER (dans cet ordre)" lists 1 to 17. None of them is 'sites'.
            // So I should probably make site_id nullable and NOT constrained for now, or constrained if I knew where it was.
            // Given the task is strict on "conforme au CdCF", and I don't have sites table yet, I will create the column but not constraint it yet to avoid error.
            // Or better, just integer nullable.
            $table->unsignedBigInteger('site_id')->nullable();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->enum('type', ['central', 'annexe', 'pharmacie']);
            $table->foreignId('responsable_id')->nullable()->constrained('users');
            $table->string('adresse')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_magasins');
    }
};
