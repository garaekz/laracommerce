<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer', 'exists:cart_items,id'],
            'quantity' => ['required', 'numeric', 'integer', 'min:0'],
        ];
    }
}
