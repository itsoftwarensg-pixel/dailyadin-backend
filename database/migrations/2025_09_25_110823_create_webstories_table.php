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
        Schema::create('webstories', function (Blueprint $table) {
            $table->id();
            $table->integer('webstory_category_id');
            $table->string('title');
            $table->string('image');
            $table->string('credit_by')->nullable();
            $table->tinyInteger('status')->default(1); // 1 = active, 2 = inactive
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webstories');
    }
};
