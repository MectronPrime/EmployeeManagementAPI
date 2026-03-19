<?php

namespace Modules\Employees\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Employees\app\Http\Requests\AddEmployeeRequest;
use Modules\Employees\app\Services\EmployeeService;

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

    // update(), destroy(), search() → Day 02
}