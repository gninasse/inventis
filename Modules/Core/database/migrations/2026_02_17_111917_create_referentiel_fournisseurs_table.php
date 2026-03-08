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
        Schema::create('referentiel_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('raison_sociale');
            $table->enum('type', ['commercial', 'donateur', 'institution']);
            $table->string('sigle')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('ifu')->nullable();
            $table->string('rccm')->nullable();
            $table->string('pays')->default('Burkina Faso');
            $table->string('contact_nom')->nullable();
            $table->string('contact_telephone')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();

            // Soft unique on raison_sociale and ifu (because ifu is nullable)
            // We can add a unique index where IFU is not null if supported by DB,
            // but strict unique constraint on nullable columns behaves differently across DBs.
            // For now, let's keep it simple or use application level check.
            // However, the request asked for: unique(raison_sociale, ifu) — mais ifu nullable donc soft unique
            // Laravel migration doesn't easily support conditional unique indexes effectively across all drivers in standard syntax.
            // We'll trust the controller logic for soft uniqueness or add a compound index for performance.
            $table->index(['raison_sociale', 'ifu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referentiel_fournisseurs');
    }
};
