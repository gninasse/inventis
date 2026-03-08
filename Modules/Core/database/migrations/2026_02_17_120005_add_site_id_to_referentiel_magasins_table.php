<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referentiel_magasins', function (Blueprint $table) {
            if (! Schema::hasColumn('referentiel_magasins', 'site_id')) {
                $table->foreignId('site_id')->nullable()->constrained('organisation_sites');
            }
        });
    }

    public function down(): void
    {
        Schema::table('referentiel_magasins', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
