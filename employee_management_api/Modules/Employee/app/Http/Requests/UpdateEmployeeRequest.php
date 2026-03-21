<?php

namespace Modules\Employee\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

  
    public function rules()
    {
      $employeeId = $this->route('id');

        return [
            'phone'                  => ['sometimes', 'string', "unique:employees,phone,{$employeeId}"],
            'monthly_salary_package' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}