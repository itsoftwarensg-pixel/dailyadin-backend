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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->integer('photo_album_id');
            // $table->foreignId('photo_album_id')->constrained('photo_albums')->onDelete('cascade');
            $table->string('caption')->nullable();
            $table->string('photographer')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('photo_of_the_day')->default(2);
            $table->tinyInteger('show_at_home')->default(2);
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
        Schema::dropIfExists('photos');
    }
};
