<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\ConsultantRepositoryInterface;
use App\Domain\Models\CD\Consultant;
use App\Exceptions\EntryNotFoundException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Str;

class EloquentConsultantRepository implements ConsultantRepositoryInterface
{
    public function getConsultantList(): Collection
    {
        $consultants = Consultant::query()->with('user');

        // search by name (full name)
        if (request()->has('name')) {

            // get the name
            $name = request()->get('name');

            // trim & convert to lowercase
            $name = strtolower(trim($name));

            // search after ignoring the case
            $consultants->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);
        }

        // filter by clinic id
        if (request()->has('clinic_id')) {
            $clinic_id = request()->get('clinic_id');
            $consultants->where('clinic_id', $clinic_id);
        }

        return $consultants->get();
    }

    public function getConsultantById(int $id): Consultant|Builder|null
    {
        $consultant = Consultant::query()->find($id);
        if (!$consultant)
            return null;

        return $consultant;
    }

    public function createConsultant(array $data): Consultant|Builder|null
    {

        $eloquentUserRepository = new EloquentUserRepository();
        $user = $eloquentUserRepository->createUser([
            'user_type_id' => '2',
            'email' => $data['email'],
            'username' => $this->generateUniqueUsername($data['first_name']),
            'password' => $this->generatePassword()
        ]);

        return Consultant::query()->create([
            'user_id' => $user['user_id'],
            'clinic_id' => $data['clinic_id'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
        ])->load('user');
    }

    public function updateConsultant(int $id, array $data): Consultant|Builder|null
    {
        $consultant = Consultant::query()->with('user')->find($id);
        if (!$consultant) return null;

        $consultant['clinic_id'] = $data['clinic_id'] ?? $consultant['clinic_id'];
        $consultant['first_name'] = $data['first_name'] ?? $consultant['first_name'];
        $consultant['last_name'] = $data['last_name'] ?? $consultant['last_name'];
        $consultant['phone_number'] = $data['phone_number'] ?? $consultant['phone_number'];
        $consultant['user']['email'] = $data['email'] ?? $consultant['user']['email'];
        $consultant['address'] = $data['address'] ?? $consultant['address'];
        $consultant['birth_date'] = $data['birth_date'] ?? $consultant['birth_date'];
        $consultant->save();
        $consultant->user->save();

        return $consultant;

    }

    /**
     * @throws \Throwable
     */
    public function deleteConsultant($id): Consultant|Builder|null
    {
        $consultant = Consultant::query()->find($id);
        if (!$consultant)
            return null;

        $user = $consultant->user;

        if ($user) {
            DB::beginTransaction();
            try {
                $consultant->delete();

                $user->delete();

                DB::commit();

            } catch (Exception) {

                DB::rollback();
                $consultant["message"] = "Error occurred while deleting consultant";
                return null;
            }
        }

        // TODO Cancel all consultant appointments

        return $consultant;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getStatistics($id): array|null
    {
        try {
            $consultant = Consultant::query()
                ->where('id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            throw new EntryNotFoundException("consultant with id $id not found");
        }

        $eloquentAppointmentRepository = new EloquentAppointmentRepository();
        $appointments = $eloquentAppointmentRepository->getAppointmentList();

        foreach ($appointments as $appointment){
            $appointment['consultant_id'] = $appointment->getConsultantId();
        }

        $consultant_appointments = $appointments->where('consultant_id','=',$id);

        $completed_appointments = $consultant_appointments->where('status_id','=',4)->values();;
        $cancelled_by_consultant_appointments = $consultant_appointments->whereIn('status_id',[3,8])->values();;
        $cancelled_by_customers_appointments = $consultant_appointments->whereIn('status_id',[1,7])->values();;

        $unknown_appointments = $consultant_appointments->where('status_id','=',11)->values();

        return [
            'completed_appointments' => count($completed_appointments),
            'cancelled_by_consultant_appointments' => count($cancelled_by_consultant_appointments),
            'cancelled_by_customers_appointments' => count($cancelled_by_customers_appointments),
            'unknown_appointments' => count($unknown_appointments),
        ];
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getMonthlyStatistics($id) : array|null
    {
        try {
            $consultant = Consultant::query()
                ->where('id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            throw new EntryNotFoundException("consultant with id $id not found");
        }

        $eloquentAppointmentRepository = new EloquentAppointmentRepository();
        $appointments = $eloquentAppointmentRepository->getAppointmentList();

        foreach ($appointments as $appointment){
            $appointment['consultant_id'] = $appointment->getConsultantId();
        }

        $consultant_appointments = $appointments->where('consultant_id','=',$id);

        // Initialize the response data array
        $responseData = [];

        // List of month names
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];

        // Count appointments with status = 4 for each month
        foreach ($months as $month) {
            $count = $consultant_appointments->filter(function ($appointment) use ($month) {
                return $appointment->status_id === 4 &&
                    date('F', strtotime($appointment->workDay->day_date)) === $month;
            })->count();

            // Add data to the response array
            $responseData[] = [
                'name' => $month,
                'عدد المواعيد' => $count,
            ];
        }

        // Return the response data
        return $responseData;

    }


    function generateUniqueUsername($firstName): string
    {

        $random_number = rand(100, 999);

        $username = strtolower($firstName) . $random_number;

        $eloquentUserRepository = new EloquentUserRepository();
        $users = $eloquentUserRepository->getUserList()->pluck('username');
        if ($users->contains('username', $username)) {
            return $this->generateUniqueUsername($firstName);
        }

        return $username;
    }

    function generatePassword(): string
    {
        return Str::random(8);
    }
}
