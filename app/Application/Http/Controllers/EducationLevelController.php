<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\EducationLevelResource;
use App\Domain\Services\EducationLevelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class EducationLevelController extends Controller
{
    private EducationLevelService $EducationLevelService;

    public function __construct(EducationLevelService $EducationLevelService)
    {
        $this->EducationLevelService = $EducationLevelService;
    }

    public function index(): JsonResponse
    {
        $educationLevels = $this->EducationLevelService->getEducationLevelList();
        return response()->json([
            'data'=>EducationLevelResource::collection($educationLevels)
            ], 200);
    }
}
