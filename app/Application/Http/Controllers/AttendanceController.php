<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\AttendanceResource;
use App\Domain\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    private AttendanceService $AttendanceService;

    public function __construct(AttendanceService $AttendanceService)
    {
        $this->AttendanceService = $AttendanceService;
    }

    public function index(): JsonResponse
    {
        $attendances = $this->AttendanceService->getAttendanceList();
        return response()->json([
            'data'=>new AttendanceResource($attendances)
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $attendances = $this->AttendanceService->getAttendanceById($id);

        if(!$attendances){
            return response()->json(['message'=>'attendances not found']
                , 404);
        }

        return response()->json([
            'data'=> new AttendanceResource($attendances)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['required','integer','exists:employees,emp_id'],
            'state' => ['boolean'],
            'attendance_time' => ['required','date_format:H:i:s'],
            'attendance_date' => ['required','date_format:Y-m-d'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $attendance = $this->AttendanceService->createAttendance(request()->all());
        if($attendance['message']){
            return response()->json([
                'message' => $attendance['message'],
                'data'=> new AttendanceResource($attendance)
            ], 400);
        }

        return response()->json([
            'data'=> new AttendanceResource($attendance)
            ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['integer','exists:employees,emp_id'],
            'state' => ['boolean'],
            'attendance_time' => ['date_format:H:i:s'],
            'attendance_date' => ['date_format:Y-m-d'],
            'status' => ['boolean']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $attendance = $this->AttendanceService->updateAttendance($id, request()->all());
        if(!$attendance){
            return response()->json(['message'=>'attendance not found']
                , 404);
        }

        return response()->json([
            'data'=> new AttendanceResource($attendance)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $attendance = $this->AttendanceService->deleteAttendance($id);

        if(!$attendance){
            return response()->json(['message'=>'attendance not found']
                , 404);
        }

        return response()->json([
            'data'=> new AttendanceResource($attendance)
            ], 200);
    }

    public function showEmployeeAttendance(int $emp_id): JsonResponse
    {
        $attendances = $this->AttendanceService->getAttendanceByEmpId($emp_id);

        if(!$attendances){
            return response()->json(['message'=>'no attendances for this employee']
                , 404);
        }

        return response()->json([
            'data'=> new AttendanceResource($attendances)
        ], 200);
    }
}
