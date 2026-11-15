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
        Schema::create('bn_contents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('secondary_category_ids')->nullable();
            $table->tinyInteger('category_home_position')->default(1);
            $table->tinyInteger('top_inner')->default(1);

            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->integer('sub_category_id_pos')->nullable();
            $table->unsignedBigInteger('special_category_id')->nullable();
            $table->integer('special_category_position')->nullable();

            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('district_id_old')->nullable();
            $table->integer('district_id_pos')->nullable();
            $table->unsignedBigInteger('upazila_id')->nullable();

            $table->string('heading')->nullable();
            $table->string('sub_heading')->nullable();

//            $table->tinyInteger('is_custom_url')->default(2);
//            $table->string('custom_url')->nullable();

            $table->unsignedBigInteger('writer_id')->nullable();
            $table->string('writers')->nullable();
            $table->string('initial')->nullable();

            $table->string('meta_title')->nullable();
            $table->longText('meta_keywords')->nullable();
            $table->longText('meta_description')->nullable();

            $table->longText('content_brief')->nullable();
            $table->longText('content_details')->nullable();

            $table->string('sm_image_path')->nullable();
            $table->string('bg_image_path')->nullable();
            $table->string('webp_image_path')->nullable();

            $table->string('bg_image_caption')->nullable();
            $table->string('bg_image_alt_text')->nullable();

            $table->string('all_tags')->nullable();
            $table->string('tag_ids')->nullable();

            $table->string('sound_path')->nullable();
            $table->string('video_path')->nullable();

            $table->tinyInteger('show_content')->default(1);
            $table->tinyInteger('show_in_scroll')->default(2);
            $table->tinyInteger('show_in_latest')->default(1);
            $table->tinyInteger('show_in_menu')->default(2);
            $table->tinyInteger('show_in_mobile')->default(1);

            $table->tinyInteger('editable')->default(1);
            $table->timestamp('editing_started_at')->nullable();
            $table->string('editing_by')->nullable();
            $table->integer('editing_by_id')->nullable();

            $table->unsignedBigInteger('timeline_tag_id')->nullable();
            $table->tinyInteger('is_timeline')->default(2);

            $table->unsignedBigInteger('share_count')->default(0);
            $table->longText('related_content')->nullable();

            $table->string('content_url')->nullable();
            $table->date('e_paper_date')->nullable();

            $table->string('ip_address')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps(); // created_at, updated_at
            $table->timestamp('deleted_at')->nullable(); // soft delete alternative
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bn_contents');
    }
};
