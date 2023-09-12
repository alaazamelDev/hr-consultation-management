<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\ScheduleResource;
use App\Domain\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    private ScheduleService $ScheduleService;

    public function __construct(ScheduleService $ScheduleService)
    {
        $this->ScheduleService = $ScheduleService;
    }

    public function index(): JsonResponse
    {
        $schedules = $this->ScheduleService->getScheduleList();
        return response()->json([
            'data'=>ScheduleResource::collection($schedules)
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $schedule = $this->ScheduleService->getScheduleById($id);
        if(!$schedule){
            return response()->json(['message'=>'Schedule not found']
                , 404);
        }

        return response()->json([
            'data'=> new ScheduleResource($schedule)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            "name" => ["required","max:255","string",
                Rule::unique("schedules", "name")->whereNull("deleted_at")],
            "time_in" =>"required", "date_format:H:i:s",
            "time_out" => "required", "date_format:H:i:s"
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }
        $schedule = $this->ScheduleService->createSchedule(request()->all());
        return response()->json([
            'data'=> new ScheduleResource($schedule)
            ], 201);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
        "name" => ["max:255","string",
            Rule::unique("schedules", "name")->whereNull("deleted_at")->ignore($id, 'schedule_id')],
        "time_in" => "date_format:H:i:s",
        "time_out" => "date_format:H:i:s"
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=>$errors
            ], 400);
        }

        $schedule = $this->ScheduleService->updateSchedule($id, request()->all());
        if(!$schedule){
            return response()->json(['message'=>'Schedule not found']
                , 404);
        }

        return response()->json([
            'data'=> new ScheduleResource($schedule)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $schedule = $this->ScheduleService->deleteSchedule($id);
        if(!$schedule){
            return response()->json(['message'=>'Schedule not found']
                , 404);
        }

        if(!empty($schedule['employees'])){
            return response()->json([
                'message' => 'one or more employees associated with this Schedule',
                'data' => new ScheduleResource($schedule)
                ], 400);
        }

        return response()->json([
            'data'=> new ScheduleResource($schedule)
            ], 200);
    }
}
