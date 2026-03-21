<?php

namespace Modules\Employees\app\Services;

use Modules\Employee\app\Repositories\EmployeeInterface as RepositoriesEmployeeInterface;

class EmployeeService
{
    // public function __construct(
    //     protected EmployeeInterface $employeeRepository
    // ) {}

    public function __construct(
        protected RepositoriesEmployeeInterface $employeeRepository
    ) {}

    /**
     * Calculate monthly tax based on salary package.
     *
     * >= 150,000  → 150,000 × 5%
     * >= 100,000  → 150,000 × 3%
     * <  100,000  → 0
     */
    public function calculateMonthlyTax(float $salary): float
    {
        if ($salary >= 150000) {
            return 150000 * 0.05; // 7,500
        }

        if ($salary >= 100000) {
            return 150000 * 0.03; // 4,500
        }

        return 0.0;
    }

    /**
     * Calculate yearly increasing bonus based on designation.
     *
     * Manager   → salary × 5%
     * Senior    → salary × 3%
     * Associate → salary × 1%
     * Intern    → 0
     */
    public function calculateYearlyBonus(float $salary, string $designation): float
    {
        return match ($designation) {
            'Manager'   => $salary * 0.05,
            'Senior'    => $salary * 0.03,
            'Associate' => $salary * 0.01,
            default     => 0.0, // Intern
        };
    }

    /**
     * Create an employee — computes tax, bonus, and net salary before storing.
     */
    public function createEmployee(array $validated): int
    {
        $salary = (float) $validated['monthly_salary_package'];

        $tax    = $this->calculateMonthlyTax($salary);
        $bonus  = $this->calculateYearlyBonus($salary, $validated['designation']);
        $net    = $salary - $tax;

        return $this->employeeRepository->create([
            ...$validated,
            'monthly_salary_package'  => $salary,
            'monthly_tax_value'       => $tax,
            'yearly_increasing_bonus' => $bonus,
            'monthly_net_salary'      => $net,
        ]);
    }

    /**
     * Return all employees with the yearly net salary appended.
     *
     * Yearly net salary = (monthly net salary × 12) + yearly bonus
     */
    public function getAllEmployees(): array
    {
        $employees = $this->employeeRepository->all();

        return array_map(function (object $employee) {
            return [
                'id'                      => $employee->id,
                'name'                    => $employee->name,
                'email'                   => $employee->email,
                'phone'                   => $employee->phone,
                'designation'             => $employee->designation,
                'monthly_salary_package'  => $employee->monthly_salary_package,
                'monthly_tax_value'       => $employee->monthly_tax_value,
                'yearly_increasing_bonus' => $employee->yearly_increasing_bonus,
                'monthly_net_salary'      => $employee->monthly_net_salary,
                'yearly_net_salary'       => ($employee->monthly_net_salary * 12)
                    + $employee->yearly_increasing_bonus,
            ];
        }, $employees);
    }

    //delete and employee by ID
    public function deleteEmployee(int $id): bool
    {
        return $this->employeeRepository->delete($id);
    }

    // search for an employee by phone number 
    public function searchByPhone(string $phone): ?array
    {
        $employee = $this->employeeRepository->findByPhone($phone);

        if (! $employee) {
            return null;
        }

        return [
            'id'                      => $employee->id,
            'name'                    => $employee->name,
            'email'                   => $employee->email,
            'phone'                   => $employee->phone,
            'designation'             => $employee->designation,
            'monthly_salary_package'  => $employee->monthly_salary_package,
            'monthly_tax_value'       => $employee->monthly_tax_value,
            'yearly_increasing_bonus' => $employee->yearly_increasing_bonus,
            'monthly_net_salary'      => $employee->monthly_net_salary,
            'yearly_net_salary'       => ($employee->monthly_net_salary * 12) + $employee->yearly_increasing_bonus,
        ];
    }

    public function updateEmployee(int $id, array $validated): bool
    {
        $employee = $this->employeeRepository->findById($id);

        if (! $employee) {
            return false;
        }

        $salary = $validated['monthly_salary_package'] ?? $employee->monthly_salary_package;
        $designation = $validated['designation'] ?? $employee->designation;

        $tax    = $this->calculateMonthlyTax($salary);
        $bonus  = $this->calculateYearlyBonus($salary, $designation);
        $net    = $salary - $tax;

        return $this->employeeRepository->update($id, [
            ...$validated,
            'monthly_salary_package'  => $salary,
            'monthly_tax_value'       => $tax,
            'yearly_increasing_bonus' => $bonus,
            'monthly_net_salary'      => $net,
        ]);
    }
}
