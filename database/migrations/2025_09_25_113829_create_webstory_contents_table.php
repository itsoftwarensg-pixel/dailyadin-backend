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
        Schema::create('webstory_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('webstory_id');
            $table->string('image');
            $table->string('caption');
            $table->string('description');
            $table->tinyInteger('status')->default(1); // 1 = active, 2 = inactive
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webstory_contents');
    }
};
