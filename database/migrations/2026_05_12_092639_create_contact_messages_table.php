<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 120);
            $table->string('email', 180);
            $table->string('telefon', 60)->nullable();
            $table->text('nachricht');
            $table->boolean('gelesen')->default(false);
            $table->timestamps();

            $table->index('gelesen');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
