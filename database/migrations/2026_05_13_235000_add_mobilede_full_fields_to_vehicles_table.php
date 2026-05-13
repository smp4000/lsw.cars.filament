<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('hsn', 10)->nullable()->after('fahrgestellnummer');
            $table->string('tsn', 10)->nullable()->after('hsn');
            $table->string('produktionsdatum', 10)->nullable()->after('erstzulassung');
            $table->boolean('schiebetuer')->default(false)->after('tueren');
            $table->string('lackierung', 30)->nullable()->after('farbe_hersteller');
            $table->boolean('metallic')->default(false)->after('lackierung');
            $table->boolean('nichtraucher')->default(false)->after('unfallfrei');
            $table->boolean('scheckheftgepflegt')->default(false)->after('nichtraucher');
            $table->boolean('verkehrstauglich')->default(true)->after('scheckheftgepflegt');
            $table->boolean('unfallschaden')->default(false)->after('verkehrstauglich');
            $table->string('garantie', 80)->nullable()->after('unfallschaden');
            $table->decimal('verbrauch_innerorts', 5, 1)->nullable()->after('energieverbrauch');
            $table->decimal('verbrauch_ausserorts', 5, 1)->nullable()->after('verbrauch_innerorts');
            $table->string('co2_klasse', 2)->nullable()->after('co2_emissionen');
            $table->decimal('energiekosten', 8, 2)->nullable()->after('co2_klasse');
            $table->string('fahrzeugtyp', 30)->nullable()->after('karosserie');
            $table->string('mwst', 30)->nullable()->after('preis');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'hsn', 'tsn', 'produktionsdatum', 'schiebetuer', 'lackierung',
                'metallic', 'nichtraucher', 'scheckheftgepflegt', 'verkehrstauglich',
                'unfallschaden', 'garantie', 'verbrauch_innerorts', 'verbrauch_ausserorts',
                'co2_klasse', 'energiekosten', 'fahrzeugtyp', 'mwst',
            ]);
        });
    }
};
