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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('baureihe', 80)->nullable()->after('karosserie');
            $table->string('ausstattungslinie', 80)->nullable()->after('baureihe');
            $table->string('herkunft', 80)->nullable()->after('ausstattungslinie');
            $table->string('antriebsart', 60)->nullable()->after('getriebe');
            $table->decimal('energieverbrauch', 5, 1)->nullable()->after('hubraum');
            $table->decimal('co2_emissionen', 6, 1)->nullable()->after('energieverbrauch');
            $table->string('schadstoffklasse', 30)->nullable()->after('co2_emissionen');
            $table->string('umweltplakette', 30)->nullable()->after('schadstoffklasse');
            $table->string('klimatisierung', 60)->nullable()->after('schiebedach');
            $table->string('einparkhilfe_detail', 60)->nullable()->after('klimatisierung');
            $table->string('airbags', 120)->nullable()->after('einparkhilfe_detail');
            $table->string('farbe_hersteller', 120)->nullable()->after('farbe');
            $table->string('innenausstattung', 80)->nullable()->after('farbe_hersteller');
            $table->unsignedInteger('anhaengelast_gebremst')->nullable()->after('sitze');
            $table->unsignedInteger('anhaengelast_ungebremst')->nullable()->after('anhaengelast_gebremst');
            $table->unsignedInteger('gewicht')->nullable()->after('anhaengelast_ungebremst');
            $table->string('letzte_wartung_datum', 10)->nullable()->after('hu');
            $table->unsignedInteger('letzte_wartung_km')->nullable()->after('letzte_wartung_datum');
            $table->unsignedSmallInteger('zylinder')->nullable()->after('hubraum');
            $table->unsignedSmallInteger('tankgroesse')->nullable()->after('zylinder');
            $table->boolean('unfallfrei')->default(true)->after('verkauft');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'baureihe', 'ausstattungslinie', 'herkunft', 'antriebsart',
                'energieverbrauch', 'co2_emissionen', 'schadstoffklasse', 'umweltplakette',
                'klimatisierung', 'einparkhilfe_detail', 'airbags', 'farbe_hersteller',
                'innenausstattung', 'anhaengelast_gebremst', 'anhaengelast_ungebremst',
                'gewicht', 'letzte_wartung_datum', 'letzte_wartung_km', 'zylinder',
                'tankgroesse', 'unfallfrei',
            ]);
        });
    }
};
