<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\FingerDeviceRepositoryInterface;
use App\Domain\Models\FingerDevice;
use Illuminate\Database\Eloquent\Builder;

class EloquentFingerDeviceRepository implements FingerDeviceRepositoryInterface
{
    public function getFingerDeviceList(): array
    {
        return FingerDevice::all()->toArray();
    }

    public function getFingerDeviceById(int $id): FingerDevice|null
    {
        return FingerDevice::find($id); // keep it find() without query->find()
    }

    public function createFingerDevice(array $data): FingerDevice|Builder|null
    {
        return FingerDevice::query()->create([
           "name" => $data["name"],
            "ip" => $data["ip"],
            "serialNumber" =>$data["serial"]
        ]);
    }

    public function updateFingerDevice(int $id, array $data): FingerDevice|Builder|null
    {
        return null;
    }

    public function deleteFingerDevice($id): FingerDevice|Builder|null
    {
        return null;
    }
}
