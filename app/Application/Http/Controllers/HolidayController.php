<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\HolidayResource;
use App\Domain\Services\HolidayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    private HolidayService $HolidayService;

    public function __construct(HolidayService $HolidayService)
    {
        $this->HolidayService = $HolidayService;
    }

    public function index(): JsonResponse
    {
        $holidays = $this->HolidayService->getHolidayList();
        return response()->json([
            'data'=>HolidayResource::collection($holidays)
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $holiday = $this->HolidayService->getHolidayById($id);

        if(!$holiday){
            return response()->json(['message'=>'Holiday not found']
                , 404);
        }

        return response()->json([
            'data'=> new HolidayResource($holiday)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required','string','max:50'],
            'date' => ['required','date_format:Y-m-d'],
            'is_recurring' => ['boolean'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $holiday = $this->HolidayService->createHoliday(request()->all());
        return response()->json([
            'data'=> new HolidayResource($holiday)
            ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['string','max:50'],
            'date' => ['date_format:Y-m-d'],
            'is_recurring' => ['boolean'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $holiday = $this->HolidayService->updateHoliday($id, request()->all());

        if(!$holiday){
            return response()->json(['message'=>'Holiday not found']
                , 404);
        }

        return response()->json([
            'data'=> new HolidayResource($holiday)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $holiday = $this->HolidayService->deleteHoliday($id);

        if(!$holiday){
            return response()->json(['message'=>'Holiday not found']
                , 404);
        }

        return response()->json([
            'data'=> new HolidayResource($holiday)
            ], 200);
    }
}
