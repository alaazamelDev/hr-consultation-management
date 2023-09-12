<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\FingerDeviceResource;
use App\Domain\Services\FingerDeviceService;
use App\Helpers\FingerHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BiometricDeviceController extends Controller
{
    private FingerDeviceService $FingerDeviceService;

    public function __construct(FingerDeviceService $FingerDeviceService)
    {
        $this->FingerDeviceService = $FingerDeviceService;
    }

    public function index(): JsonResponse
    {
        $fingerDevices = $this->FingerDeviceService->getFingerDeviceList();
        return response()->json([
            'data'=>FingerDeviceResource::collection($fingerDevices)
            ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $fingerDevice = $this->FingerDeviceService->getFingerDeviceById($id);
        if(!$fingerDevice){
            return response()->json(['message'=>'FingerDevice not found']
                , 404);
        }
        return response()->json([
            'data'=> new FingerDeviceResource($fingerDevice)
            ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            "name" => ["required","max:50","string"],
            "ip" => ["required", "ipv4"]
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $data=request()->all();
        $helper = new FingerHelper();
        $device = $helper->init($data["ip"]);

        if ($device->connect()) {
            // Serial Number Sample CDQ9192960002\x00
            $serial = $helper->getSerial($device);
            $data["serial"] = $serial;
            $fingerDevice = $this->FingerDeviceService->createFingerDevice($data);

            return response()->json([
                'data'=> new FingerDeviceResource($fingerDevice)
            ], 201);
        }

        else {
            return response()->json([
                'message'=> "Failed connecting to Biometric Device !"
            ], 422);
        }
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            "name" => ["max:50","string"],
            "ip" => ["ipv4"]
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $fingerDevice = $this->FingerDeviceService->getFingerDeviceById($id);

        if(!$fingerDevice){
            return response()->json(['message'=>'Finger Device not found']
                , 404);
        }

        $fingerDevice->update(request()->all());
        $fingerDevice = $this->FingerDeviceService->getFingerDeviceById($id);

        return response()->json([
            'data'=> new FingerDeviceResource($fingerDevice)
            ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $fingerDevice = $this->FingerDeviceService->getFingerDeviceById($id);
        if(!$fingerDevice){
            return response()->json(['message'=>'FingerDevice not found']
                , 404);
        }

        try {
            $fingerDevice->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message'=> "Failed to delete {$fingerDevice['name']} "
            ], 422);
        }

        return response()->json([
            'data'=> new FingerDeviceResource($fingerDevice)
            ], 200);
    }

}
