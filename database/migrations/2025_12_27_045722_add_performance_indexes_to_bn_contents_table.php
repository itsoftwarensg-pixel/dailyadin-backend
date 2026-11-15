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
        Schema::table('bn_contents', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('show_content');
            $table->index('created_at');
            $table->index(['show_content', 'created_at']);
        });

        if (Schema::hasTable('bn_total_hits')) {
            Schema::table('bn_total_hits', function (Blueprint $table) {
                $table->index('total_hits');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bn_contents', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['show_content']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['show_content', 'created_at']);
        });

        if (Schema::hasTable('bn_total_hits')) {
            Schema::table('bn_total_hits', function (Blueprint $table) {
                $table->dropIndex(['total_hits']);
            });
        }
    }
};
