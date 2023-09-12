<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Requests\EditEmployeeCredentialsRequest;
use App\Application\Http\Requests\EditEmployeeDepartmentRequest;
use App\Application\Http\Requests\EditEmployeeScheduleRequest;
use App\Application\Http\Requests\EditEmploymentStatusRequest;
use App\Application\Http\Requests\StoreEmployeeRequest;
use App\Application\Http\Resources\DepartmentResource;
use App\Application\Http\Resources\EmployeeBriefResource;
use App\Application\Http\Resources\EmployeeDetailsResource;
use App\Application\Http\Resources\EmployeeJobTitleResource;
use App\Application\Http\Resources\EmployeeResource;
use App\Application\Http\Resources\RelativeEmployeesResource;
use App\Application\Http\Resources\ScheduleResource;
use App\Domain\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $EmployeeService)
    {
        $this->employeeService = $EmployeeService;
    }

    public function index(): AnonymousResourceCollection
    {
        $employees = $this->employeeService->getEmployeeList();
        return EmployeeBriefResource::collection($employees);
    }

    public function indexList(): AnonymousResourceCollection
    {
        $employees = $this->employeeService->getAllEmployees();
        return RelativeEmployeesResource::collection($employees);
    }

    public function indexJobTitles(int $id): JsonResponse
    {
        $jobTitleHistory = $this->employeeService->getJobTitlesHistory($id);
        return response()->json($jobTitleHistory);
    }

    public function indexDepartments(int $id): JsonResponse
    {
        $departmentHistory = $this->employeeService->getDepartmentsHistory($id);
        return response()->json($departmentHistory);
    }

    public function indexLog(int $id): JsonResponse
    {
        $employee = $this->employeeService->getEmployeeById($id);
        return response()->json($employee->log);
    }

    public function indexAbsence(int $id): JsonResponse
    {
        $absenceHistory = $this->employeeService->getEmployeeAbsenceHistory($id);
        return response()->json($absenceHistory);
    }

    public function show(int $id): EmployeeDetailsResource
    {
        $employee = $this->employeeService->getEmployeeById($id);
        return new EmployeeDetailsResource($employee);
    }

    public function store(StoreEmployeeRequest $request): EmployeeDetailsResource
    {
        $validated = $request->validated();
        $employee = $this->employeeService->createEmployee($validated);
        return new EmployeeDetailsResource($employee);
    }

    public function destroy(int $id): EmployeeDetailsResource
    {
        $employee = $this->employeeService->deleteEmployee($id);
        return new EmployeeDetailsResource($employee);
    }

    public function editCredentials(EditEmployeeCredentialsRequest $request, int $id): EmployeeResource
    {
        $validated = $request->validated();
        $employee = $this->employeeService->editEmployeeCredentials($id, $validated);
        return new EmployeeResource($employee);
    }

    public function editDepartment(EditEmployeeDepartmentRequest $request, int $id): DepartmentResource
    {
        $validated = $request->validated();
        $employee = $this->employeeService->editEmployeeDepartment($id, $validated);
        return new DepartmentResource($employee->current_department);
    }

    public function editEmploymentStatus(EditEmploymentStatusRequest $request, int $id): EmployeeResource
    {
        $validated = $request->validated();
        $employee = $this->employeeService->editEmployeeEmploymentStatus($id, $validated);
        return new EmployeeResource($employee);
    }

    public function editSchedule(EditEmployeeScheduleRequest $request, int $id): ScheduleResource
    {
        $validated = $request->validated();
        $employee = $this->employeeService->editEmployeeSchedule($id, $validated);
        return new ScheduleResource($employee->schedule);
    }

    public function editPermissions(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'job_title_id' => ['sometimes',
                Rule::exists('job_titles', 'job_title_id')->whereNull('deleted_at'),
            ],
            'additional_permissions_ids' => ['sometimes', 'array'],
            'additional_permissions_ids.*' => ['integer', 'exists:permissions,perm_id'],
            'deleted_permissions_ids' => ['sometimes', 'array', 'min:1'],
            'deleted_permissions_ids.*' => ['integer', 'exists:permissions,perm_id'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors' => $errors
            ], 400);
        }
        $employee = $this->employeeService->editEmployeePermissions($id, request()->all());
        if (!$employee) {
            return response()->json([
                'message' => 'employee not found'
            ], 404);
        }
        if ($employee['status'] == 400) {
            return response()->json([
                'message' => $employee['message']
            ], 400);
        }

        return response()->json([
            'data' => new EmployeeJobTitleResource($employee)
        ], 200);
    }
}
