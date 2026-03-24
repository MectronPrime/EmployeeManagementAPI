<?php

namespace Modules\Employee\app\Repositories;

interface EmployeeInterface
{
    public function create(array $data): int;

    public function all(): array;

    public function update(String $name, array $data): bool;

    public function delete(String $name): bool;

    public function findByPhone(string $phone): ?array;
}
