<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoAlbumRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // Album
            'photo_category_id' => 'required',
            'name'              => 'required|string|max:255', // title
            'photographer_name' => 'nullable|string|max:255',
            'description'       => 'nullable|string',

            // Album photos
            'contents'                  => 'required|array|min:1', // at least one content
            'contents.*.caption'        => 'required|string',
            'contents.*.photographer'   => 'nullable|string',
        ];

        // Image rules
        if ($this->isMethod('post')) {
            // Creating new photo album: image required
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:300';

            // Album Photos
            $rules['contents.*.image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:300';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Updating: image optional
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:300';

            // Album Photos
            $rules['contents.*.image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:300';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // Album
            'name.required' => 'The photo album name is required.',
            'photo_category_id.required' => 'Please select a category.',
            'image.required' => 'The main image is required.',
            'image.image' => 'The main file must be an image.',

            // Album Photos
            'contents.required' => 'Please add at least one content item below.',
            'contents.*.caption.required' => 'Caption is required for each content item.',
            'contents.*.photographer.required' => 'photographer is required for each content item.',
            'contents.*.image.required' => 'Each content item must have an image.',
        ];
    }
}
