<?php

namespace Modules\Employees\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Employees\app\Http\Requests\AddEmployeeRequest;
use Modules\Employees\app\Services\EmployeeService;
use Modules\Employees\app\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    /**
     * POST /api/employees — Add a new employee.
     */
    public function store(AddEmployeeRequest $request): JsonResponse
    {
        $id = $this->employeeService->createEmployee($request->validated());

        return response()->json([
            'message'     => 'Employee created successfully.',
            'employee_id' => $id,
        ], 201);
    }

    /**
     * GET /api/employees — Retrieve all employees with full salary report.
     */
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getAllEmployees();

        return response()->json([
            'data' => $employees,
        ]);
    }

    // Update an employee's phone number and/or monthly salary package.
    public function update(UpdateEmployeeRequest $request, int $id): JsonResponse
    {
        $updated = $this->employeeService->updateEmployee($id, $request->validated());

        if (! $updated) {
            return response()->json(['message' => 'Employee not found.'], 404);
        }

        return response()->json(['message' => 'Employee updated successfully.']);
    }

    //Remove an employee by ID.

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->employeeService->deleteEmployee($id);

        if (! $deleted) {
            return response()->json(['message' => 'Employee not found.'], 404);
        }

        return response()->json(['message' => 'Employee deleted successfully.']);
    }

    // find an employee by phone number and return their details along with the yearly net salary.
    public function search(Request $request): JsonResponse
    {
        $phone = $request->query('phone');

        if (! $phone) {
            return response()->json(['message' => 'Phone number is required.'], 400);
        }

        $employee = $this->employeeService->searchByPhone($phone);

        if (! $employee) {
            return response()->json(['message' => 'Employee not found.'], 404);
        }
        return response()->json([
            'data' => $employee,
        ]);
    }
}
