<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\ClinicResource;
use App\Domain\Services\ClinicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ClinicController extends Controller
{
    private ClinicService $ClinicService;

    public function __construct(ClinicService $ClinicService)
    {
        $this->ClinicService = $ClinicService;
    }

    public function index(): JsonResponse
    {
        $clinics = $this->ClinicService->getClinicList();
        return response()->json([
            'data'=>ClinicResource::collection($clinics)
            ], 200);
    }
}
