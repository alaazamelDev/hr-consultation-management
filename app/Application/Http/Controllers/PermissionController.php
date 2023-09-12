<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\PermissionResource;
use App\Domain\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    private $PermissionService;

    public function __construct(PermissionService $PermissionService)
    {
        $this->PermissionService = $PermissionService;
    }

    public function index(): JsonResponse
    {
        $permissions = $this->PermissionService->getPermissionList();
        return response()->json([
            'data'=>PermissionResource::collection($permissions)
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $permission = $this->PermissionService->getPermissionById($id);
        if(!$permission){
            return response()->json(['message'=>'Permission not found']
                , 404);
        }
        return response()->json([
            'data'=> new PermissionResource($permission)
            ], 200);
    }

//    public function store(): JsonResponse
//    {
//        $permission = $this->PermissionService->createPermission(request()->all());
//        return response()->json([
//            'data'=> new PermissionResource($permission) //Modify it as needed
//            ], 200);
//    }

//    public function update(int $id): JsonResponse
//    {
//        $permission = $this->PermissionService->updatePermission($id, request()->all());
//        return response()->json([
//            'data'=> new PermissionResource($permission) //Modify it as needed
//            ], 200);
//    }

//    public function destroy(int $id): JsonResponse
//    {
//        $permission = $this->PermissionService->deletePermission($id);
//        return response()->json([
//            'data'=> new PermissionResource($permission) //Modify it as needed
//            ], 200);
//    }
}
