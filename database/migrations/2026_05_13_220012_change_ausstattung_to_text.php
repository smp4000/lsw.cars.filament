<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->text('ausstattung_serie')->nullable()->change();
            $table->text('ausstattung_sonder')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->json('ausstattung_serie')->nullable()->change();
            $table->json('ausstattung_sonder')->nullable()->change();
        });
    }
};
