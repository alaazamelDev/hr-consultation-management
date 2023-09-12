<?php

namespace App\Domain\Repositories;

use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    public function getCustomerList(): LengthAwarePaginator;

    public function getCustomerById(int $id): Customer|Builder|null;

    public function updateCustomer(int $id, array $data): Customer|Builder|null;

    public function delete(int $id): Customer|Builder|null;

    public function customersMissedAppointments(): LengthAwarePaginator;

    public function customerToggleStatus(int $id): Customer|Builder|null;

    public function userSingUp(array $data): array;

    public function addCustomerByEmployee(array $data): array;

    public function customerLogin(array $data): array;

    public function userLogout(): void;

    public function customerDetection(int $national_number): array;

    public function customerVerification(array $data): Customer|Builder|null;

    public function getStatistics(int $id): array|null;

    public function bookAnAppointmentByCustomer($appointment): Appointment|Builder|null;

    public function getCustomerAppointments(): Collection;

    public function getCustomerInfo(): Customer|Builder|null;

    public function getCustomerStatistics(): array;

}
