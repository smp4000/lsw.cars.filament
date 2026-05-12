<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('marke', 80);
            $table->string('modell', 120);
            $table->string('titel', 200);
            $table->text('beschreibung')->nullable();
            $table->decimal('preis', 10, 2)->default(0);
            $table->string('erstzulassung', 10)->nullable();
            $table->unsignedInteger('kilometerstand')->default(0);
            $table->string('kraftstoff', 40)->nullable();
            $table->string('getriebe', 40)->nullable();
            $table->unsignedInteger('leistung_kw')->nullable();
            $table->unsignedInteger('leistung_ps')->nullable();
            $table->unsignedInteger('hubraum')->nullable();
            $table->string('farbe', 60)->nullable();
            $table->unsignedTinyInteger('tueren')->nullable();
            $table->unsignedTinyInteger('sitze')->nullable();
            $table->string('karosserie', 40)->nullable();
            $table->string('zustand', 30)->default('Gebraucht');
            $table->string('hu', 10)->nullable();
            $table->unsignedTinyInteger('anzahl_halter')->nullable();
            $table->boolean('klimaanlage')->default(false);
            $table->boolean('navigation')->default(false);
            $table->boolean('sitzheizung')->default(false);
            $table->boolean('einparkhilfe')->default(false);
            $table->boolean('tempomat')->default(false);
            $table->boolean('anhaengerkupplung')->default(false);
            $table->boolean('ledersitze')->default(false);
            $table->boolean('schiebedach')->default(false);
            $table->boolean('verfuegbar')->default(true);
            $table->boolean('verkauft')->default(false);
            $table->timestamps();

            $table->index('marke');
            $table->index('preis');
            $table->index('verfuegbar');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
