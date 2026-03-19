<?php

namespace Modules\Employee\app\Repositories;

interface EmployeeInterface
{
    //Insert a new employee record and return the new ID.
    public function create(array $data): int;

    // Retrieve all employee records.
    
    public function all(): array;

    //Find an employee by ID.
    public function findById(int $id): ?object;

    // Update phone and/or salary for a given employee.
    public function update(int $id, array $data): bool;

    //Delete an employee record.
    public function delete(int $id): bool;

    //Search employees by phone number (exact match).
    public function findByPhone(string $phone): ?object;
}
