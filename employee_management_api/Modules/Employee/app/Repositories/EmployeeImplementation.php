<?php

namespace Modules\Employee\app\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Employee\Repositories\EmployeeInterface;

class EmployeeImplementation implements EmployeeInterface
{
    //insert new employee using query builder
    public function create(array $data): int
    {
        return DB::table('employees')->insertGetId([
            'name'                    => $data['name'],
            'email'                   => $data['email'],
            'phone'                   => $data['phone'],
            'designation'             => $data['designation'],
            'monthly_salary_package'  => $data['monthly_salary_package'],
            'monthly_tax_value'       => $data['monthly_tax_value'],
            'yearly_increasing_bonus' => $data['yearly_increasing_bonus'],
            'monthly_net_salary'      => $data['monthly_net_salary'],
        ]);
    }
    // Retrieve all employees ordered by most recently
    public function all(): array
    {
        return DB::table('employees')
            ->orderBy('createdat' ,'desc')
            -get()
            ->toArray();
    }

}   