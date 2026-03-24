<?php

namespace Modules\Employee\app\Repositories;

use Modules\Employee\app\Models\Employee;


class EmployeeImplementation implements EmployeeInterface
{
    public function create(array $data): int
    {
        $employee = new Employee();

        $employee->name = $data['name'];
        $employee->email = $data['email'];
        $employee->phone = $data['phone'];
        $employee->designation = $data['designation'];
        $employee->monthly_salary_package = $data['monthly_salary_package'];
        $employee->monthly_tax_value = $data['monthly_tax_value'];
        $employee->yearly_increasing_bonus = $data['yearly_increasing_bonus'];
        $employee->monthly_net_salary = $data['monthly_net_salary'];
        $employee->save();
        
        return $employee->id;
    }
    // Retrieve all employees ordered by most recently
    public function all(): array
    {
        return Employee::orderBy('created_at', 'desc')
        ->get()
        ->toArray();
    }

    //update employee phone and/or salary
    public function update($name, array $data): bool
    {
        $employee = Employee::find($name);
        
        if (!$employee) {
            return false;
        }
        
        if (isset($data['phone'])) {
            $employee->phone = $data['phone'];
        }
        
        if (isset($data['monthly_salary_package'])) {
            $employee->monthly_salary_package = $data['monthly_salary_package'];
        }
        
        if (isset($data['monthly_net_salary'])) {
            $employee->monthly_net_salary = $data['monthly_net_salary'];
        }
        
        return $employee->save();

    }
    //delete an employee record
    public function delete(String $name): bool
    {
        $employee = Employee::find($name);
        if (!$employee) {
            return false;
        }
        return $employee->delete();
    }
    //search employee by phone number
    public function findByPhone(string $phone): ?array
     {
        $employee = Employee::where('phone', $phone)->first();
        return $employee ? $employee->toArray() : null;
     }
    
}   