<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Application\Http\Resources\CustomerResource;
use App\Application\Http\Resources\PermissionResource;
use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\AppointmentStatus;
use App\Domain\Models\CD\Customer;
use App\Domain\Repositories\CustomerRepositoryInterface;
use App\Exceptions\EntryNotFoundException;
use App\Notifications\LoginNotification;
use App\Utils\StorageUtilities;
use Exception;
use Ichtrojan\Otp\Models\Otp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Notifications\EmailVerificationNotification;


class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function getCustomerList(): LengthAwarePaginator
    {
        $customers = Customer::query();


        if (request()->has('name')) {
            $name = request()->query('name');

            $name = trim($name);

            $name = strtolower($name);

            $customers->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);

        }

        if (request()->has('usingApp')) {
            $result = request()->query('usingApp');

            $result = trim($result);

            $result = strtolower($result);

            if ($result == "true") {
                $customers->where('isUsingApp', '=', true);
            }

        }
        if (request()->has('username')) {
            $customers->where('username', 'like', '%' . request()->input('username') . '%');
        }
        if (request()->has('education_level_id')) {
            $customers->where('education_level_id', '=', request()->input('education_level_id'));
        }
        if (request()->has('martial_status')) {
            $customers->where('martial_status', '=', request()->input('martial_status'));
        }
        if (request()->has('job')) {
            $customers->where('job', 'like', '%' . request()->input('job') . '%');
        }
        if (request()->has('birth_date')) {
            $customers->where('birth_date', '=', request()->input('birth_date'));
        }
        if (request()->has('from_birth_date')) {
            $customers->where('birth_date', '>=', request()->input('from_birth_date'));
        }
        if (request()->has('to_birth_date')) {
            $customers->where('birth_date', '<=', request()->input('to_birth_date'));
        }
        if (request()->has('num_of_children')) {
            $customers->where('num_of_children', '=', request()->input('num_of_children'));
        }
        if (request()->has('national_number')) {
            $customers->where('national_number', '=', request()->input('national_number'));
        }
        if (request()->has('verified')) {
            $customers->where('verified', '=', request()->input('verified'));
        }
        if (request()->has('blocked')) {
            $customers->where('blocked', '=', request()->input('blocked'));
        }

        return $customers->paginate(10);
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getCustomerById(int $id): Customer|Builder|null
    {
        try {
            $customer = Customer::query()
                ->with('educationLevel')
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (Exception $e) {
            throw new EntryNotFoundException("customer with id $id not found");
        }

        return $customer;
    }

    /**
     * @throws EntryNotFoundException
     */
    public function updateCustomer(int $id, array $data): Customer|Builder|null
    {
        try {
            $customer = Customer::query()
                ->where('id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            StorageUtilities::deletePersonalPhoto($data['profile_picture']);
            throw new EntryNotFoundException("customer with id $id not found");
        }

        if (isset($data['profile_picture'])) {
            StorageUtilities::deletePersonalPhoto($customer['profile_picture']);
        }

        $customer->update([
            'first_name' => $data['first_name'] ?? $customer->first_name,
            'last_name' => $data['last_name'] ?? $customer->last_name,
            'education_level_id' => $data['education_level_id'] ?? $customer->education_level_id,
            'email' => $data['email'] ?? $customer->email,
//            'username' => $data['username'] ?? $customer->username,
//            'password' => isset($data['password']) ? Hash::make($data['password']) : $customer->password,
            'job' => $data['job'] ?? $customer->job,
            'birth_date' => $data['birth_date'] ?? $customer->birth_date,
            'phone' => $data['phone'] ?? $customer->phone,
            'phone_number' => $data['phone_number'] ?? $customer->phone_number,
            'martial_status' => $data['martial_status'] ?? $customer->martial_status,
            'num_of_children' => $data['num_of_children'] ?? $customer->num_of_children,
            'national_number' => $data['national_number'] ?? $customer->national_number,
            'profile_picture' => $data['profile_picture'] ?? $customer->profile_picture,
            'verified' => $data['verified'] ?? $customer->verified,
            'blocked' => $data['blocked'] ?? $customer->blocked,
        ]);
        return $customer;
    }

    public function delete(int $id): Customer|Builder|null
    {
        try {
            $customer = Customer::query()
                ->where('id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            throw new EntryNotFoundException("customer with id $id not found");
        }

        $customer->delete();

        return $customer;
    }

    public function customersMissedAppointments(): LengthAwarePaginator
    {
        $customers = Customer::query()
            ->whereHas('appointments.status', function ($query) {
                $query->whereIn('id', [1, 7]);
            })
            ->withCount(['appointments as missed_appointment_count' => function ($query) {
                $query->whereHas('status', function ($query) {
                    $query->whereIn('id', [1, 7]);
                });
            }])
            ->orderBy('missed_appointment_count', 'desc');

        if (request()->has('name')) {
            $name = request()->query('name');

            $name = trim($name);

            $name = strtolower($name);

            $customers->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);
        }

        if (request()->has('order_by_date')) {
            $result = request()->query('order_by_date');
            $result = trim($result);
            $result = strtolower($result);

            if ($result == "true") {
                $customers->with(['appointments' => function ($query) {
                    $query->orderByDesc('start_time');
                }]);
            }
        }


        return $customers->paginate(10);
    }

    public function customerToggleStatus(int $id): Customer|Builder|null
    {
        try {
            $customer = Customer::query()
                ->where('id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            throw new EntryNotFoundException("customer with id $id not found");
        }

        $customer->blocked = !$customer->blocked;
        $customer->save();

        return $customer;
    }

    public function userSingUp(array $data): array
    {
        $new_customer = Customer::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'phone' => $data['phone'] ?? null,
            'phone_number' => $data['phone_number'],

            'education_level_id' => $data['education_level_id'],
            'martial_status' => $data['martial_status'],
            'job' => $data['job'],
            'num_of_children' => $data['num_of_children'],

            'profile_picture' => $data['profile_picture'] ?? null,
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),

            'verified' => false,
            'blocked' => false,
        ]);


        //verify email
        if (isset($data['email'])) {
            $new_customer->notify(new EmailVerificationNotification());
        }

        return [
            'token' => $new_customer->createToken('customer_auth_token')->plainTextToken,
            'activated' => false,
            'customer_data' => new CustomerResource($new_customer),
        ];
    }

    public function addCustomerByEmployee(array $data): array
    {
        $new_customer = Customer::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'education_level_id' => $data['education_level_id'],
            'email' => $data['email'] ?? null,
            'username' => $this->generateUniqueUsername($data['first_name']),
            'password' => Hash::make($this->generatePassword()),
            'job' => $data['job'],
            'birth_date' => $data['birth_date'],
            'phone' => $data['phone'] ?? null,
            'phone_number' => $data['phone_number'],
            'martial_status' => $data['martial_status'],
            'num_of_children' => $data['num_of_children'],
            'national_number' => $data['national_number'] ?? null,
            'profile_picture' => $data['profile_picture'] ?? null,

            'verified' => isset($data['national_number']),
            'blocked' => false,
        ]);
        return [
            'token' => $new_customer->createToken('customer_auth_token')->plainTextToken,
            'customer_data' => $new_customer,
        ];
    }

    /**
     * @throws EntryNotFoundException
     */
    public function customerLogin(array $data): array
    {
        // data contains email(can be either username or email) and password
        $customer = Customer::query()
            ->where('email', '=', $data['email'])
            ->orWhere('username', '=', $data['email'])
            ->first();

        if (!$customer) {
            throw new EntryNotFoundException('المستخدم غير موجود', 404);
        }

        if (!Hash::check($data['password'], $customer->password)) {
            throw new EntryNotFoundException("كلمة المرور غير صحيحة", 401);
        }

        // Check if the customer is verified
        $activated = DB::table('otps')
            ->where('identifier', $customer->email)
            ->where('valid', false)->exists();

        // if the customer is not verified, send otp
        if (!$activated) {
//            $customer->notify(new EmailVerificationNotification());
            return [
                'token' => $customer->createToken('customer_auth_token')->plainTextToken,
                'activated' => false,
                'customer' => new CustomerResource($customer),
            ];
        } else {
//            $customer->notify(new LoginNotification());
        }
        return [
            'token' => $customer->createToken('customer_auth_token')->plainTextToken,
            'activated' => true,
            'customer' => new CustomerResource($customer),
        ];
    }

    /**
     * @throws EntryNotFoundException
     */
    public function userLogout(): void
    {
        // Retrieve the currently authenticated customer
        $customer = Auth::guard('customer')->user();

        // Check if the customer is authenticated
        if (!$customer) {
            throw new EntryNotFoundException('المتسخدم غير موجود', 404);
        }

        // Revoke the token associated with the customer
        $customer->currentAccessToken()->delete();
    }

    public function customerDetection(int $national_number): array
    {
        $result = Customer::query()->where('national_number', '=', $national_number)->first();

        if ($result == null) {
            $status = ['status' => 1];
        } else if (!$result['isUsingApp']) {
            $status = ['status' => 2, 'customer_id' => $result['id'], 'created_at' => date('Y-m-d', strtotime($result['created_at']))];
        } else
            $status = ['status' => 3, 'customer_id' => $result['id'], 'created_at' => date('Y-m-d', strtotime($result['created_at']))];

        return $status;
    }

    /**
     * @throws BadRequestException
     */
    public function customerVerification(array $data): Customer|Builder|null
    {
        $accounts = Customer::query()->where('national_number', '=', $data['national_number'])->get();

        // Status 1
        if (sizeof($accounts) == 0) {
            $app_account = Customer::query()->where('id', '=', $data['app_account_id'])->first();
            if ($app_account != null) {
                $app_account['national_number'] = $data['national_number'];
                $app_account['verified'] = true;
                $app_account->save();

                return $app_account;
            } else {
                return null; // TODO NO APP ACCOUNT
            }
        } else {
            // Status 2
            $account = $accounts
                ->where('verified', '=', true)
                ->where('isUsingApp', '=', false)->first();
            if ($account != null) {
                $app_account = Customer::query()->where('id', '=', $data['app_account_id'])->first();
                if ($app_account != null) {
                    $eloquentAppointmentRepository = new EloquentAppointmentRepository();
                    $appointments = $eloquentAppointmentRepository->getAppointmentList()
                        ->where('customer_id', '=', $app_account['id']);

                    foreach ($appointments as $appointment) {
                        $appointment['customer_id'] = $account['id'];
                        $appointment->save();
                    }
                    $app_account->delete();

                    $account['isUsingApp'] = true;
                    $account['email'] = $app_account['email'];
                    $account['username'] = $app_account['username'];
                    $account['password'] = $app_account['password'];

                    $account->save();
                    return $account;
                } else {
                    return null; // TODO NO APP ACCOUNT
                }
            }
        }

        $accounts[0]['message'] = 'Customer already has verified application account';
        $accounts[0]['status'] = '400';
        return $accounts[0];
    }

    /**
     * @throws EntryNotFoundException
     */
    public function getStatistics(int $id): array|null
    {
        try {
            $customer = Customer::query()
                ->where('id', '=', $id)
                ->firstOrFail();

        } catch (Exception) {
            throw new EntryNotFoundException("customer with id $id not found");
        }

        $eloquentAppointmentRepository = new EloquentAppointmentRepository();
        $appointments = $eloquentAppointmentRepository->getAppointmentList();

        $consultant_appointments = $appointments->where('customer_id', '=', $id);
        foreach ($consultant_appointments as $consultant_appointment) {
            $consultant_appointment['clinic_name'] = $consultant_appointment->getClinicName();
        }

        $eloquentClinicRepository = new EloquentClinicRepository();
        $clinics = $eloquentClinicRepository->getClinicList();


        $responseData = [];

        for ($i = 0; $i < count($clinics); $i++) {
            $responseData[$i]['clinic_name'] = $clinics[$i]->name;
            $responseData[$i]['completed_appointments'] = $consultant_appointments
                ->where('clinic_name', '=', $clinics[$i]->name)
                ->where('status_id', '=', 4)->count();
            $responseData[$i]['cancelled_appointments'] = $consultant_appointments
                ->where('clinic_name', '=', $clinics[$i]->name)
                ->whereIn('status_id', [1, 7])->count();
        }

        return $responseData;

    }

    function generateUniqueUsername($firstName): string
    {

        $random_number = rand(100, 999);

        $username = strtolower($firstName) . $random_number;

        $customers = Customer::query()->get();
        if ($customers->contains('username', $username)) {
            $username = $this->generateUniqueUsername($firstName);
            return $username;
        }

        return $username;
    }

    function generatePassword(): string
    {
        return Str::random(12);
    }

    public function bookAnAppointmentByCustomer($appointment): Appointment|Builder|null
    {
        $appointment->update([
            'status_id' => AppointmentStatus::STATUS_RESERVED,
            'customer_id' => Auth::guard()->user()->id,
        ]);
//        $appointment->save();

        return $appointment;
    }

    public function getCustomerAppointments(): Collection
    {
        return Appointment::query()->where('customer_id', '=', Auth::guard()->user()->id)->get();
    }

    public function getCustomerInfo(): Customer|Builder|null
    {
        return Customer::query()->where('id', '=', Auth::guard()->user()->id)->first();
    }

    public function getCustomerStatistics(): array
    {
        // get number of completed appointment , number of cancelled appointment , number of missed appointment by customer & missed by consultant , number of total appointment
        $customer = Auth::guard()->user()->id;
        $appointments = Appointment::query()->where('customer_id', '=', $customer)->get();
        $completed_appointments = $appointments->where('status_id', '=', AppointmentStatus::STATUS_COMPLETED)->count();
        $cancelled_by_customer_appointments = $appointments->where('status_id','=', AppointmentStatus::STATUS_CANCELED_BY_CUSTOMER)->count();
        $cancelled_by_consultant_appointments = $appointments->where('status_id', '=',AppointmentStatus::STATUS_CANCELED_BY_CONSULTANT)->count();
        $missed_by_customer_appointments = $appointments->where('status_id', '=', AppointmentStatus::STATUS_MISSED_BY_CUSTOMER)->count();
        $missed_by_consultant_appointments = $appointments->where('status_id', '=', AppointmentStatus::STATUS_MISSED_BY_CONSULTANT)->count();
        $reserved_appointments = $appointments->where('status_id', '=', AppointmentStatus::STATUS_RESERVED)->count();
        $total_appointments = $appointments->count();

        return [
            'completed_appointments' => $completed_appointments,
            'reserved_appointments' => $reserved_appointments,
            'cancelled_by_customer_appointments' => $cancelled_by_customer_appointments,
            'cancelled_by_consultant_appointments' => $cancelled_by_consultant_appointments,
            'missed_by_customer_appointments' => $missed_by_customer_appointments,
            'missed_by_consultant_appointments' => $missed_by_consultant_appointments,
            'total_appointments' => $total_appointments,
        ];

    }

}
