<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->unsignedSmallInteger('sortierung')->default(0);
        });

        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('equipment_categories')->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('mobile_key', 120)->nullable()->unique();
            $table->unsignedSmallInteger('sortierung')->default(0);

            $table->unique(['category_id', 'name']);
        });

        Schema::create('vehicle_equipment', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_item_id')->constrained('equipment_items')->cascadeOnDelete();

            $table->primary(['vehicle_id', 'equipment_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_equipment');
        Schema::dropIfExists('equipment_items');
        Schema::dropIfExists('equipment_categories');
    }
};
