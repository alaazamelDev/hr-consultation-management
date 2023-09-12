<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\AbsenceResource;
use App\Domain\Services\AbsenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AbsenceController extends Controller
{
    private AbsenceService $AbsenceService;

    public function __construct(AbsenceService $AbsenceService)
    {
        $this->AbsenceService = $AbsenceService;
    }

    public function index(): JsonResponse
    {
        $absences = $this->AbsenceService->getAbsenceList();

        return response()->json([
            'data'=>AbsenceResource::collection($absences["data"]),
            'pagination' => [
                'current_page' => $absences["current_page"],
                'first_page_url' => $absences["first_page_url"],
                'from' => $absences["from"],
                'last_page' => $absences["last_page"],
                'last_page_url' => $absences["last_page_url"],
                'links' => $absences["links"],
                'next_page_url' => $absences["next_page_url"],
                'path' => $absences["path"],
                'per_page' => $absences["per_page"],
                'prev_page_url' => $absences["prev_page_url"],
                'to' => $absences["to"],
                'total' => $absences["total"],
            ],
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $absence = $this->AbsenceService->getAbsenceById($id);
        if(!$absence){
            return response()->json(['message'=>'Absence not found']
                , 404);
        }

        return response()->json([
            'data'=> new AbsenceResource($absence)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'emp_id' => ['required','integer','exists:employees,emp_id'],
            'absence_date' => ['date_format:Y-m-d'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $absence = $this->AbsenceService->createAbsence(request()->all());
        if($absence['message']){
            return response()->json([
                'message' => $absence['message'],
                'data'=> new AbsenceResource($absence)
            ], 400);
        }

        return response()->json([
            'data'=> new AbsenceResource($absence)
            ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $rules = [
            'status' => ['required','integer','exists:absence_statuses,absence_status_id'],
        ];

        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ],400);
        }

        $absence = $this->AbsenceService->updateAbsenceStatus($id, request()->input('status'));
        if(!$absence){
            return response()->json(['message'=>'Absence not found']
                , 404);
        }

        return response()->json([
            'data'=> new AbsenceResource($absence)
            ], 200);
    }

    public function showEmployeeAbsences(int $id): JsonResponse
    {
        $absence = $this->AbsenceService->getEmployeeAbsences($id);

        if(!$absence)
            return response()->json(['message'=>'Employee not found']
                , 404);

        return response()->json([
            'data'=> AbsenceResource::collection($absence)
            ], 200);
    }
}
