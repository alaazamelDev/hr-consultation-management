<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\LeaveResource;
use App\Domain\Services\LeaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    private LeaveService $LeaveService;

    public function __construct(LeaveService $LeaveService)
    {
        $this->LeaveService = $LeaveService;
    }

    public function index(): JsonResponse
    {
        $leaves = $this->LeaveService->getLeaveList();

        return response()->json([
            'data'=>new LeaveResource($leaves)
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $leave = $this->LeaveService->getLeaveById($id);

        if(!$leave){
            return response()->json(['message'=>'Leave not found']
                , 404);
        }
        return response()->json([
            'data'=> new LeaveResource($leave)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['required','integer','exists:employees,emp_id'],
            'state' => ['boolean'],
            'leave_time' => ['required','date_format:H:i:s'],
            'leave_date' => ['required','date_format:Y-m-d'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $leave = $this->LeaveService->createLeave(request()->all());

        if(!$leave){
            return response()->json([
                'message' => "Employee has not checked-in in this day"
            ], 400);
        }

        if($leave['message']){
            return response()->json([
                'message' => $leave['message'],
                'data'=> new LeaveResource($leave)
            ], 400);
        }

        return response()->json([
            'data'=> new LeaveResource($leave)
        ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['required','integer','exists:employees,emp_id'],
            'state' => ['boolean'],
            'leave_time' => ['required','date_format:H:i:s'],
            'leave_date' => ['required','date_format:Y-m-d'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $leave = $this->LeaveService->updateLeave($id, request()->all());

        if(!$leave){
            return response()->json(['message'=>'Leave not found']
                , 404);
        }

        return response()->json([
            'data'=> new LeaveResource($leave)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $leave = $this->LeaveService->deleteLeave($id);

        if(!$leave){
            return response()->json(['message'=>'Leave not found']
                , 404);
        }

        return response()->json([
            'data'=> new LeaveResource($leave)
            ], 200);
    }
}
