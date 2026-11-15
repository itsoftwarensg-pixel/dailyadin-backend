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
        Schema::create('ramadan_times', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ramadan 2026
            $table->string('name')->unique(); // ramadan_2026
            $table->string('hijri_year')->unique(); // 1447
            $table->string('ramadan_start_date')->unique(); // 2026-02-17
            $table->json('days');
            $table->json('division_offsets');
            $table->longText('footnote')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadan_times');
    }
};
