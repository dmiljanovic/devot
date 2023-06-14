<?php

namespace App\Http\Requests\Expense;

use App\Traits\RequestQueryParams;
use Illuminate\Foundation\Http\FormRequest;

class ShowAndDeleteExpenseRequest extends FormRequest
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
            'expense' => 'required|exists:expenses,id'
        ];
    }
}
