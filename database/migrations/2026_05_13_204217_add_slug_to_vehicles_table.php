<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->after('titel');
        });

        foreach (DB::table('vehicles')->get() as $v) {
            $base = Str::slug($v->titel);
            $slug = $base;
            $i = 2;
            while (DB::table('vehicles')->where('slug', $slug)->where('id', '!=', $v->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('vehicles')->where('id', $v->id)->update(['slug' => $slug]);
        }

        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('slug', 255)->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
