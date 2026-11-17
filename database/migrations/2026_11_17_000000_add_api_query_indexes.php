<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bn_contents', function (Blueprint $table) {
            $table->index(['category_id', 'show_content', 'created_at'], 'bn_contents_category_visible_created_idx');
            $table->index(['sub_category_id', 'show_content', 'created_at'], 'bn_contents_subcategory_visible_created_idx');
            $table->index(['district_id', 'show_content', 'created_at'], 'bn_contents_district_visible_created_idx');
            $table->index(['upazila_id', 'show_content', 'created_at'], 'bn_contents_upazila_visible_created_idx');
            $table->index(['writer_id', 'show_content', 'created_at'], 'bn_contents_writer_visible_created_idx');
            $table->index(['show_in_scroll', 'show_content', 'created_at'], 'bn_contents_scroll_visible_created_idx');
        });

        Schema::table('bn_manage_positions', function (Blueprint $table) {
            $table->index(['position_type', 'position_type_id'], 'bn_manage_positions_type_id_idx');
        });

        Schema::table('bn_content_tag', function (Blueprint $table) {
            $table->index(['tag_id', 'bn_content_id'], 'bn_content_tag_tag_content_idx');
            $table->index(['bn_content_id', 'tag_id'], 'bn_content_tag_content_tag_idx');
        });

        if (Schema::hasTable('bn_total_hits')) {
            Schema::table('bn_total_hits', function (Blueprint $table) {
                $table->index('bn_content_id', 'bn_total_hits_content_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::table('bn_contents', function (Blueprint $table) {
            $table->dropIndex('bn_contents_category_visible_created_idx');
            $table->dropIndex('bn_contents_subcategory_visible_created_idx');
            $table->dropIndex('bn_contents_district_visible_created_idx');
            $table->dropIndex('bn_contents_upazila_visible_created_idx');
            $table->dropIndex('bn_contents_writer_visible_created_idx');
            $table->dropIndex('bn_contents_scroll_visible_created_idx');
        });

        Schema::table('bn_manage_positions', function (Blueprint $table) {
            $table->dropIndex('bn_manage_positions_type_id_idx');
        });

        Schema::table('bn_content_tag', function (Blueprint $table) {
            $table->dropIndex('bn_content_tag_tag_content_idx');
            $table->dropIndex('bn_content_tag_content_tag_idx');
        });

        if (Schema::hasTable('bn_total_hits')) {
            Schema::table('bn_total_hits', function (Blueprint $table) {
                $table->dropIndex('bn_total_hits_content_idx');
            });
        }
    }
};
