<?php

namespace App\Http\Requests\Category;

use App\Traits\RequestQueryParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    use RequestQueryParams;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category' => 'required|integer|exists:categories,id',
            'name' => 'required|string|min:4',
        ];
    }
}
