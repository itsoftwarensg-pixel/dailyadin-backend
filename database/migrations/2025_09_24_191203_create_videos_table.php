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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->integer('video_category_id')->nullable();
            $table->tinyInteger('video_type')->nullable();
            $table->string('title')->nullable();
            $table->string('embed_code');
            $table->longText('description')->nullable();
            $table->string('photo')->nullable(); // In UI thumbnail is showing from YouTube API.
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
        Schema::dropIfExists('videos');
    }
};
