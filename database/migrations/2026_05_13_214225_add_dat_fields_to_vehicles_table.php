<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('fahrgestellnummer', 17)->nullable()->after('id');
            $table->string('dat_ecode', 20)->nullable()->after('fahrgestellnummer');
            $table->json('ausstattung_serie')->nullable()->after('schiebedach');
            $table->json('ausstattung_sonder')->nullable()->after('ausstattung_serie');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['fahrgestellnummer', 'dat_ecode', 'ausstattung_serie', 'ausstattung_sonder']);
        });
    }
};
