<?php

namespace App\Http\Requests\Expense;

use App\Traits\RequestQueryParams;
use Illuminate\Foundation\Http\FormRequest;

class AggregateExpenseRequest extends FormRequest
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
            'term' => 'required|in:last_month,last_quarter,last_year,this_year'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'term.in' =>
                'Term for selected period is not valid. Acceptable terms are: last_month, last_quarter, last_year, this_year',
        ];
    }
}
