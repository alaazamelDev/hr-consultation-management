<?php

namespace App\Domain\Services;

use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\Customer;
use App\Domain\Repositories\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CustomerService
{
    private CustomerRepositoryInterface $CustomerRepository;

    public function __construct(CustomerRepositoryInterface $CustomerRepository)
    {
        $this->CustomerRepository = $CustomerRepository;
    }

    public function getCustomerList(): LengthAwarePaginator
    {
        return $this->CustomerRepository->getCustomerList();
    }

    public function getCustomerById(int $id): Customer|Builder|null
    {
        return $this->CustomerRepository->getCustomerById($id);
    }

    public function updateCustomer(int $id, array $data): Customer|Builder|null
    {
        return $this->CustomerRepository->updateCustomer($id, $data);
    }

    public function deleteCustomer($id): Customer|Builder|null
    {
        return $this->CustomerRepository->delete($id);
    }

    public function userSingUp(array $data): array
    {
        return $this->CustomerRepository->userSingUp($data);
    }

    public function addCustomerByEmployee(array $data): array
    {
        return $this->CustomerRepository->addCustomerByEmployee($data);
    }

    public function customerLogin(array $data): array
    {
        return $this->CustomerRepository->customerLogin($data);
    }

    public function userLogout(): void
    {
        $this->CustomerRepository->userLogout();
    }

    public function customersMissedAppointments(): LengthAwarePaginator
    {
        return $this->CustomerRepository->customersMissedAppointments();
    }

    public function customerToggleStatus(int $id): Customer|Builder|null
    {
        return $this->CustomerRepository->customerToggleStatus($id);
    }

    public function customerDetection(int $national_number): array
    {
        return $this->CustomerRepository->customerDetection($national_number);
    }

    public function customerVerification(array $data): Customer|Builder|null
    {
        return $this->CustomerRepository->customerVerification($data);
    }

    public function getStatistics(int $id): array|null
    {
        return $this->CustomerRepository->getStatistics($id);
    }

    public function bookAnAppointmentByCustomer($appointment): Appointment|Builder|null
    {
        return $this->CustomerRepository->bookAnAppointmentByCustomer($appointment);
    }

    public function getCustomerAppointments(): Collection
    {
        return $this->CustomerRepository->getCustomerAppointments();
    }

    public function getCustomerInfo(): Customer|Builder|null
    {
        return $this->CustomerRepository->getCustomerInfo();
    }

    public function getCustomerStatistics(): array
    {
        return $this->CustomerRepository->getCustomerStatistics();
    }
}
