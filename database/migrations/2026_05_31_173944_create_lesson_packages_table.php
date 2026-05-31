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
        Schema::create('lesson_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('package_type', ['rijlespakket', 'losse_les', 'theoriepakket']);
            $table->unsignedSmallInteger('lesson_count')->nullable();
            $table->unsignedInteger('lesson_minutes')->nullable();
            $table->unsignedInteger('price_cents');
            $table->unsignedTinyInteger('vat_percent')->default(21);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_packages');
    }
};
