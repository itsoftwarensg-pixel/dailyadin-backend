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
        Schema::create('star_programs', function (Blueprint $table) {
            $table->id();
            $table->string('identity')->nullable()->comment('Name or Identity of the star');
            $table->text('details')->nullable();
            $table->text('quote')->nullable();
            $table->string('photo')->nullable();
            $table->string('video_link')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 2=Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('star_programs');
    }
};
