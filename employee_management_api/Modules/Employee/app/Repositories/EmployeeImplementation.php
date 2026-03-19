<?php

namespace Modules\Employee\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Employee\Repositories\EmployeeInterface;

class EmployeeImplementation implements EmployeeInterface
{
    public function getAllEmployees()
    {
        return DB::table('employees')->get();
    }

    public function getEmployeeById($id)
    {
        return DB::table('employees')->where('id', $id)->first();
    }

    public function createEmployee(array $employeeData)
    {
        return DB::table('employees')->insertGetId($employeeData);
    }

    public function updateEmployee($id, array $employeeData)
    {                       
        return DB::table('employees')->where('id', $id)->update($employeeData);
    }

    public function deleteEmployee($id)
    {
        return DB::table('employees')->where('id', $id)->delete();
    }
}   