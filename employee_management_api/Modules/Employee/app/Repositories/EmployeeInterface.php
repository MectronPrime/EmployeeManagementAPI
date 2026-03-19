<?php

namespace Modules\Employee\Repositories;

interface EmployeeInterface
{
    public function getAllEmployees();

    public function getEmployeeById($id);

    public function createEmployee(array $employeeData);

    public function updateEmployee($id, array $employeeData);

    public function deleteEmployee($id);
}