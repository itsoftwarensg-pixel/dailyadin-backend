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
        Schema::create('bn_special_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->text('end_note')->nullable();
            $table->longText('description')->nullable();
            $table->integer('sorting')->default(1);
            $table->string('image_icon')->nullable();
            $table->string('image_menu')->nullable();
            $table->string('image_cover_home')->nullable();
            $table->string('image_cover_inner')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Show, 2=Hide');
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bn_special_categories');
    }
};
