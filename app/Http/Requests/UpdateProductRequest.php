<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->product->id),
            ],
            'price' => ['sometimes', 'numeric', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:1024'],
        ];
    }
}
