<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BnContentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'              => 'required|integer|exists:bn_categories,id',
            'secondary_category_ids'   => 'nullable',
            'category_home_position'   => 'nullable|integer|min:1',
            'category_inner_position'  => 'nullable|integer|min:1',
            'sub_category_id'          => 'nullable|integer|exists:bn_sub_categories,id',
//            'sub_category_id_pos'      => 'nullable|integer|min:1',
            'special_category_id'      => 'nullable|integer',
            'special_category_position'=> 'nullable|integer|min:1',
            'district_id'              => 'nullable|integer',
//            'district_id_old'          => 'nullable|integer',
//            'district_id_pos'          => 'nullable|integer|min:1',
            'upazila_id'               => 'nullable|integer',
            'heading'                  => 'required|string',
            'sub_heading'              => 'nullable|string',
//            'is_custom_url'            => 'boolean',
//            'custom_url'               => 'nullable|string|max:255',
            'writer_id'                => 'nullable|integer|exists:writers,id',
            'writers'                  => 'nullable|string',
            'initial'                  => 'nullable|string|max:50',
            'meta_title'               => 'nullable|string|max:255',
            'meta_keywords'            => 'nullable|string',
            'meta_description'         => 'nullable|string',
            'content_brief'            => 'nullable|string',
            'content_details'          => 'required|string',
//            'sm_image_path'            => 'nullable|string',
            'bg_image_path'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:600',
//            'webp_image_path'          => 'nullable|string',
            'bg_image_caption'         => 'nullable|string|max:255',
//            'bg_image_alt_text'        => 'nullable|string|max:255',
            'sound_path'               => 'nullable|string',
            'video_path'               => 'nullable|string',
            'show_content'             => 'integer',
            'show_in_scroll'           => 'integer',
            'show_in_latest'           => 'integer',
//            'show_in_menu'             => 'boolean',
            'show_in_mobile'           => 'integer',
            'editable'                 => 'integer',
            'editing_started_at'       => 'nullable|date',
            'editing_by'               => 'nullable|string|max:255',
            'editing_by_id'            => 'nullable|integer',
//            'timeline_tag_id'          => 'nullable|integer',
//            'is_timeline'              => 'boolean',
//            'share_count'              => 'nullable|integer',
//            'related_content'          => 'nullable|string',
//            'content_url'              => 'nullable|string|max:255',
            'e_paper_date'             => 'nullable|date',
            'ip_address'               => 'nullable|ip',
            'created_by'               => 'nullable|integer',
            'updated_by'               => 'nullable|integer',
        ];

    }
}
