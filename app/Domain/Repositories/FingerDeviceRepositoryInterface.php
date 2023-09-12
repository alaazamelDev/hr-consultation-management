<?php

namespace App\Domain\Repositories;

use App\Domain\Models\FingerDevice;
use Illuminate\Database\Eloquent\Builder;

interface FingerDeviceRepositoryInterface
{
    public function getFingerDeviceList(): array;

    public function getFingerDeviceById(int $id): FingerDevice|null;

    public function createFingerDevice(array $data): FingerDevice|Builder|null;

    public function updateFingerDevice(int $id, array $data): FingerDevice|Builder|null;

    public function deleteFingerDevice($id): FingerDevice|Builder|null;
}
