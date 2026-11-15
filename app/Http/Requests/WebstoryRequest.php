<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebstoryRequest extends FormRequest
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
            // Webstory
            'webstory_category_id'      => 'required',
            'title'                     => 'required|string|max:255',
            'credit_by'                 => 'nullable|string|max:255',

            // Webstory contents
            'contents'                  => 'required|array|min:1', // at least one content
            'contents.*.caption'        => 'required|string',
            'contents.*.description'    => 'required|string',
        ];

        // Image rules
        if ($this->isMethod('post')) {
            // Creating new webstory: image required
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:300';

            // Webstory contents
            $rules['contents.*.image'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:300';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Updating: image optional
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:300';

            // Webstory contents
            $rules['contents.*.image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:300';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'The webstory title is required.',
            'webstory_category_id.required' => 'Please select a category.',
            'image.required' => 'The main image is required.',
            'image.image' => 'The main file must be an image.',
            'contents.required' => 'Please add at least one photo item below.',
            'contents.*.caption.required' => 'Caption is required for each content item.',
            'contents.*.description.required' => 'Description is required for each content item.',
            'contents.*.image.required' => 'Each content item must have an image.',
        ];
    }
}
