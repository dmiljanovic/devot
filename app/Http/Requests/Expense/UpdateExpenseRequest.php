<?php

namespace App\Http\Requests\Expense;

use App\Traits\RequestQueryParams;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'expense' => 'required|integer|exists:expenses,id',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|string|min:8|max:256',
        ];
    }
}
