<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\WorkingDayResource;
use App\Domain\Services\WorkingDayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class WorkingDayController extends Controller
{
    private WorkingDayService $WorkingDayService;

    public function __construct(WorkingDayService $WorkingDayService)
    {
        $this->WorkingDayService = $WorkingDayService;
    }

    public function index(): JsonResponse
    {
        $workingDays = $this->WorkingDayService->getWorkingDayList();
        return response()->json([
            'data'=>WorkingDayResource::collection($workingDays)
            ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            "status" => ["required","bool"]
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $workingDay = $this->WorkingDayService->updateWorkingDay($id, request()->all());

        if(!$workingDay){
            return response()->json(['message'=>'Working day not found']
                , 404);
        }

        return response()->json([
            'data'=> new WorkingDayResource($workingDay)
            ], 200);
    }

}
