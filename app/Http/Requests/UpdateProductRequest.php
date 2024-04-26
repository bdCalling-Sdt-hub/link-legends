<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        return [
            //
            'category_id' => '',
            'subcategory_id' => '',
            'product_name' => '',
            'url' => '',
            'regular_price' => 'numeric',
            'sale_price' => 'nullable|numeric',
            'color' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string',
            'product_rating_avg' => 'nullable|string',
            'total_review' => 'nullable|integer',
            'size' => 'nullable',
            'width' => 'nullable',
            'depth' => 'nullable|string',
            'exterior_condition' => 'nullable|string',
            'material_condition' => 'nullable|string',
            'material' => 'nullable|string',
            'manufactured_by' => 'nullable|string',
            'details' => 'nullable|string',
            'design_name' => 'nullable|string',
            'height' => 'nullable|string',
            'strap_drop' => 'nullable|string',
            'interior_condition' => 'nullable|string',
            'product_code' => 'nullable|integer',
            'interior_color' => 'nullable|string',
            'our_picks' => 'nullable|boolean',
            'target_audience' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rule for multiple images
        ];
    }
}
