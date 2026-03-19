<?php

namespace Modules\Employee\app\Repositories;

use Illuminate\Support\Facades\DB;

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
            ->orderBy('created_at' ,'desc')
            ->get()
            ->toArray();
    }
 
    //find a single employee by id
    public function findById(int $id): ?object
    {
        return DB::table('employees')
            ->where('id', $id)
            ->first();
    }

    //update employee phone and/or salary
    public function update(int $id, array $data): bool
    {
        $updateData = [];
        if (isset($data['phone'])) {
            $updateData['phone'] = $data['phone'];
        }
        if (isset($data['monthly_salary_package'])) {
            $updateData['monthly_salary_package'] = $data['monthly_salary_package'];
        }
        if (empty($updateData)) {
            return false; // No data to update
        }
        return DB::table('employees')
            ->where('id', $id)
            ->update($updateData) > 0;
    }
    //delete an employee record
    public function delete(int $id): bool
    {
        return DB::table('employees')
            ->where('id', $id)
            ->delete() > 0;
    }
    //search employee by phone number
    public function findByPhone(string $phone): ?object
    {
        return DB::table('employees')
            ->where('phone', $phone)
            ->first();
    }
}   