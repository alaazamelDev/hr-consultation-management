<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Resources\DepartmentResource;
use App\Domain\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    private DepartmentService $DepartmentService;

    public function __construct(DepartmentService $DepartmentService)
    {
        $this->DepartmentService = $DepartmentService;
    }

    public function index(): JsonResponse
    {
        $items = $this->DepartmentService->getList();
        return response()->json([
                'data' => DepartmentResource::collection($items)]
            , 200);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->DepartmentService->getById($id);
        if (!$item) {
            return response()->json(['message' => 'Department not found']
                , 404);
        }
        return response()->json([
            'data' => new DepartmentResource($item)
        ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required', 'max:50', 'string',
                Rule::unique('departments', 'name')->whereNull('deleted_at')],
            'description' => ['max:255', 'string', 'nullable']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data'=> $errors
            ], 400);
        }
        $item = $this->DepartmentService->create(request()->all());
        return response()->json([
            'data' => new DepartmentResource($item)
        ], 201);

    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['max:50', 'string',
                Rule::unique('departments', 'name')->whereNull('deleted_at')->ignore($id, 'dep_id')],
            'description' => ['max:255', 'string', 'nullable']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data'=>$errors
                ], 400);
        }

        $item = $this->DepartmentService->getById($id);
        if (!$item) {
            return response()->json(['message' => 'Department not found']
                , 404);
        }
        $item = $this->DepartmentService->update($id, request()->all());
        return response()->json([
            'data' => new DepartmentResource($item)
        ], 200);

    }

    public function destroy(int $id): JsonResponse
    {
        $item = $this->DepartmentService->getById($id);
        if (!$item) {
            return response()->json(['message' => 'Department not found']
                , 404);
        }
        $item = $this->DepartmentService->delete($id);
        if ($item['employees_count'] > 0) {
            return response()->json([
                'message' => 'There is one or more employees in the department',
            ], 400);
        }
        if ($item['message']) {
            return response()->json([
                'message' => $item['message'],
            ], 400);
        }
        return response()->json([
            'data' => new DepartmentResource($item),
        ], 200);
    }
}
