<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\UnRegisteredAccountBriefResource;
use App\Application\Http\Resources\UnRegisteredAccountResource;
use App\Domain\Services\UnRegisteredAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UnRegisteredAccountController extends Controller
{
    private UnRegisteredAccountService $UnRegisteredAccountService;

    public function __construct(UnRegisteredAccountService $UnRegisteredAccountService)
    {
        $this->UnRegisteredAccountService = $UnRegisteredAccountService;
    }

    public function index(): AnonymousResourceCollection
    {
        $unRegisteredAccounts = $this->UnRegisteredAccountService->getUnRegisteredAccountList();

        return UnRegisteredAccountBriefResource::collection($unRegisteredAccounts);
    }

    public function show(int $id): JsonResponse
    {
        $unRegisteredAccount = $this->UnRegisteredAccountService->getUnRegisteredAccountById($id);
        return response()->json([
            'data'=> new UnRegisteredAccountResource($unRegisteredAccount)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'app_id' => 'required|exists:appointments,id',
            'name' => 'required|string|min:2|max:50',
            'phone_number' => 'required|string|min:10|max:15'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => $errors
            ], 400);
        }

        $unRegisteredAccount = $this->UnRegisteredAccountService->createUnRegisteredAccount(request()->all());
        return response()->json([
            'data'=> new UnRegisteredAccountResource($unRegisteredAccount)
            ], 200);
    }

//    public function update(int $id): JsonResponse
//    {
//        $unRegisteredAccount = $this->UnRegisteredAccountService->updateUnRegisteredAccount($id, request()->all());
//        return response()->json([
//            'data'=> new UnRegisteredAccountResource($unRegisteredAccount) //Modify it as needed
//            ], 200);
//    }

    public function destroy(int $id): JsonResponse
    {
        $unRegisteredAccount = $this->UnRegisteredAccountService->deleteUnRegisteredAccount($id);
        return response()->json([
            'data'=> new UnRegisteredAccountResource($unRegisteredAccount)
            ], 200);
    }
}
