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
        Schema::create('home_video_sections', function (Blueprint $table) {
            $table->id();
            $table->string('heading')->nullable();
            $table->text('embed_code');
            $table->tinyInteger('type')->comment('1=YouTube, 2=Facebook');
            $table->tinyInteger('is_live')->default(2)->comment('1=Live, 2=Not Live');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_video_sections');
    }
};
