<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\EmployeeVacationResource;
use App\Domain\Services\EmployeeVacationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeVacationController extends Controller
{
    private EmployeeVacationService $EmployeeVacationService;

    public function __construct(EmployeeVacationService $EmployeeVacationService)
    {
        $this->EmployeeVacationService = $EmployeeVacationService;
    }

    public function index(): JsonResponse
    {
        $employeesVacations = $this->EmployeeVacationService->getEmployeeVacationList();
        return response()->json([
            'data'=> EmployeeVacationResource::collection($employeesVacations["data"]),
            'pagination' => [
                'current_page' => $employeesVacations["current_page"],
                'first_page_url' => $employeesVacations["first_page_url"],
                'from' => $employeesVacations["from"],
                'last_page' => $employeesVacations["last_page"],
                'last_page_url' => $employeesVacations["last_page_url"],
                'links' => $employeesVacations["links"],
                'next_page_url' => $employeesVacations["next_page_url"],
                'path' => $employeesVacations["path"],
                'per_page' => $employeesVacations["per_page"],
                'prev_page_url' => $employeesVacations["prev_page_url"],
                'to' => $employeesVacations["to"],
                'total' => $employeesVacations["total"],
            ],
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $employeeVacation = $this->EmployeeVacationService->getEmployeeVacationById($id);

        if(!$employeeVacation){
            return response()->json(['message'=>'Vacation not found']
                , 404);
        }

        return response()->json([
            'data'=> new EmployeeVacationResource($employeeVacation)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['required','integer','exists:employees,emp_id'],
            'start_date' => ['required','date',
                Rule::unique('employee_vacations', 'start_date')->whereNull('deleted_at')],
            'total_days' => ['required','integer','min:1'],
            'remaining_days' => ['integer','min:0']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $employeeVacation = $this->EmployeeVacationService->createEmployeeVacation(request()->all());
        return response()->json([
            'data'=> new EmployeeVacationResource($employeeVacation)
            ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['integer','exists:employees,emp_id'],
            'start_date' => ['date',
                Rule::unique('employee_vacations', 'start_date')->whereNull('deleted_at')],
            'total_days' => ['integer','min:1'],
            'remaining_days' => ['integer','min:0']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }
        $employeeVacation = $this->EmployeeVacationService->updateEmployeeVacation($id, request()->all());

        if(!$employeeVacation){
            return response()->json(['message'=>'Vacation not found']
                , 404);
        }

        return response()->json([
            'data'=> new EmployeeVacationResource($employeeVacation)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $employeeVacation = $this->EmployeeVacationService->deleteEmployeeVacation($id);

        if(!$employeeVacation){
            return response()->json(['message'=>'Vacation not found']
                , 404);
        }

        if($employeeVacation["message"] !== null){
            return response()->json([
                'message' => $employeeVacation["message"],
                'data'=> new EmployeeVacationResource($employeeVacation)
            ], 400);
        }

        return response()->json([
            'data'=> new EmployeeVacationResource($employeeVacation)
            ], 200);
    }

    public function showEmployeeVacations($emp_id): JsonResponse
    {
        $employeeVacations = $this->EmployeeVacationService->getEmployeeVacations($emp_id);

        if(isset($employeeVacations["message"])){
            return response()->json(['message'=>'Employee not found']
                , 404);
        }

        return response()->json([
            'data'=> EmployeeVacationResource::collection($employeeVacations)
        ], 200);
    }
}
