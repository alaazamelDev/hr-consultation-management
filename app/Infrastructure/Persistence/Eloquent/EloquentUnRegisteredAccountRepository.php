<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\UnRegisteredAccountRepositoryInterface;
use App\Domain\Models\CD\UnRegisteredAccount;
use App\Exceptions\EntryNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUnRegisteredAccountRepository implements UnRegisteredAccountRepositoryInterface
{
    public function getUnRegisteredAccountList(): LengthAwarePaginator
    {
        return UnRegisteredAccount::query()->with('appointment.status')->paginate(10);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getUnRegisteredAccountById(int $id): UnRegisteredAccount|Builder|null
    {
        try {
            $unRegisteredAccount = UnRegisteredAccount::query()
                ->with('appointment.status')
                ->with('appointment')
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (\Exception $e) {
            throw new EntryNotFoundException("un-registered account with id $id not found");
        }

        return $unRegisteredAccount;
    }

    public function bookUnRegisteredAccountAppointment(array $data): UnRegisteredAccount|Builder|null
    {

        $eloquentAppointmentRepository = new EloquentAppointmentRepository();
        $appointment = $eloquentAppointmentRepository->getAppointmentById($data['app_id']);

        $appointment->status_id = 9;
        $appointment->save();
        return UnRegisteredAccount::query()->create([
            'app_id' => $data['app_id'],
            'name' => $data['name'],
            'phone_number' => $data['phone_number'],
        ])->load('appointment.status');
    }
//
//    public function updateUnRegisteredAccount(int $id, array $data): UnRegisteredAccount|Builder|null
//    {
//        // TODO: Implement the logic to update a UnRegisteredAccount
//    }

    /**
     * @throws EntryNotFoundException
     */
    public function deleteUnRegisteredAccount($id): UnRegisteredAccount|Builder|null
    {

        try {
            $unRegisteredAccount = UnRegisteredAccount::query()
                ->with('appointment.status')
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (\Exception $e) {
            throw new EntryNotFoundException("un-registered account with id $id not found");
        }

        $unRegisteredAccount->delete();

        return $unRegisteredAccount;
    }
}
