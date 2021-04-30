<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
                'name' => 'sometimes|required|string|max:250',
                'detail' => 'nullable|string|max:1000',
                'location' => 'sometimes|required|string|max:250',
                'status' => 'nullable|max:199|in:open,assigned,completed,cancelled',
                'images' => "nullable|array",
                'images.*' => 'image|max:1999'
            ];
    }
}
