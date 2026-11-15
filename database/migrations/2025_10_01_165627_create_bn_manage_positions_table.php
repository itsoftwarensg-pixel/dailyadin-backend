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
        Schema::create('bn_manage_positions', function (Blueprint $table) {
            $table->id();
            $table->string('position_type', 50); // enum-like (use with ManagePositionEnum)
            $table->unsignedBigInteger('position_type_id')->nullable(); // e.g. category_id, tag_id. lead_position, scroll_position type doesn't need this'
            $table->text('ids')->nullable(); // can store multiple IDs as CSV/JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bn_manage_positions');
    }
};
