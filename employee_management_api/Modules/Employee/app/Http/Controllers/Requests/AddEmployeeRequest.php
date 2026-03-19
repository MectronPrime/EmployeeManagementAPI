<?php

namespace Modules\Employees\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'unique:employees,email'],
            'phone'                  => ['required', 'string', 'unique:employees,phone'],
            'designation'            => ['required', 'in:Intern,Associate,Senior,Manager'],
            'monthly_salary_package' => ['required', 'numeric', 'min:0'],
            'monthly_tax_value'      => ['nullable', 'numeric', 'min:0'],
            'yearly_increasing_bonus' => ['nullable', 'numeric', 'min:0'],
            'monthly_net_salary'     => ['required', 'numeric', 'min:0'],
        ];
    }
}