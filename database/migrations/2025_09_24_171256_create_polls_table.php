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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question_bn');
            $table->string('question_en')->nullable();
            $table->string('description_bn')->nullable();
            $table->string('description_en')->nullable();
            $table->tinyInteger('option_yes')->nullable()->default(0);
            $table->tinyInteger('option_no')->nullable()->default(0);
            $table->tinyInteger('option_no_comment')->nullable()->default(0);
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('polls');
    }
};
