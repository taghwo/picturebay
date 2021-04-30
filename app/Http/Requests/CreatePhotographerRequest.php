<?php

namespace App\Http\Requests;

use App\Rules\ProductBelongsToRequestOwner;
use Illuminate\Foundation\Http\FormRequest;

class CreatePhotographerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photographer_id' => 'required|integer|exists:users,id',
            'product_id' => ['required','integer','exists:products,id']
        ];
    }
}
