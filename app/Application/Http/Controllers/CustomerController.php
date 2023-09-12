<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Requests\AddCustomerRequest;
use App\Application\Http\Requests\CustomerLoginRequest;
use App\Application\Http\Requests\EditCustomerAfterVerification;
use App\Application\Http\Requests\EditCustomerBeforeVerification;
use App\Application\Http\Requests\UserLoginRequest;
use App\Application\Http\Requests\UserSingUpRequest;
use App\Application\Http\Resources\AppointmentResource;
use App\Application\Http\Resources\CustomerBriefResource;
use App\Application\Http\Resources\CustomerResource;
use App\Application\Http\Resources\CustomersMissedAppointments;
use App\Domain\Models\CD\Appointment;
use App\Domain\Models\CD\Customer;
use App\Domain\Services\CustomerService;
use App\Utils\StorageUtilities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    private CustomerService $CustomerService;

    public function __construct(CustomerService $CustomerService)
    {
        $this->CustomerService = $CustomerService;
    }

    public function index(): AnonymousResourceCollection
    {
        $customers = $this->CustomerService->getCustomerList();
        return CustomerBriefResource::collection($customers);
    }

    public function show(int $id): CustomerResource
    {
        $customer = $this->CustomerService->getCustomerById($id);
        return new CustomerResource($customer);
    }


//    public function updateBeforeVerified(EditCustomerBeforeVerification $request, int $id): CustomerResource
//    {
//        $validated = $request->validated();
//        $customer = $this->CustomerService->updateCustomer($id, $validated);
//        return new CustomerResource($customer);
//
//    }

    public function update(EditCustomerAfterVerification $request, int $id): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = StorageUtilities::storeCustomerPhoto($data['profile_picture']);
        }

        $customer = $this->CustomerService->updateCustomer($id, $data);
        return response()->json([
            'data' => new CustomerResource($customer)
        ], 200);

    }

    public function destroy(int $id): JsonResponse
    {
        $customer = $this->CustomerService->deleteCustomer($id);

        return response()->json([
            'data' => new CustomerResource($customer)
        ]);

    }

    public function userSingUp(UserSingUpRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = StorageUtilities::storeCustomerPhoto($request['profile_picture']);
        }

        $validated = $this->CustomerService->userSingUp($validated);
        return response()->json([
            'token' => $validated['token'],
            'activated' => $validated['activated'],
            'data' => new CustomerResource($validated['customer_data'])
        ]);
    }

    public function customerLogin(CustomerLoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $data = $this->CustomerService->customerLogin($credentials);
        return response()->json($data);
    }

    public function customerLogout(): JsonResponse
    {
        $this->CustomerService->userLogout();
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    public function addCustomerByEmployee(AddCustomerRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = StorageUtilities::storeCustomerPhoto($request['profile_picture']);
        }

        // TODO : GET BACK TO HERE
//        $data = $this->CustomerService->userSingUp($data);
        $data = $this->CustomerService->addCustomerByEmployee($data);
        return response()->json([
            'token' => $data['token'],
            'data' => new CustomerResource($data['customer_data'])
        ]);
    }


    public function customersMissedAppointments(): AnonymousResourceCollection
    {
        $data = $this->CustomerService->customersMissedAppointments();
        return CustomersMissedAppointments::collection($data);
    }

    public function customerToggleStatus(int $id): JsonResponse
    {
        $customer = $this->CustomerService->customerToggleStatus($id);
        return response()->json([
            'data' => new CustomerResource($customer)
        ], 200);
    }

    public function customerDetection(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'national_number' => [
                'required',
                'string',
                'size:11',
            ],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors' => $errors
            ], 400);
        }
        $data = request()->all();
        $customerStatus = $this->CustomerService->customerDetection($data['national_number']);
        return response()->json([
            'data' => $customerStatus
        ], 200);
    }

    public function customerVerification(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'national_number' => [
                'required',
                'string',
                'size:11',
            ],
            'app_account_id' => [
                'required',
                'integer',
                'exists:customers,id',
            ]
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors' => $errors
            ], 400);
        }
        $customerStatus = $this->CustomerService->customerVerification(request()->all());

        if ($customerStatus == null) {
            return response()->json([
                'error' => 'Customer does not have application account'
            ], 404);
        }

        if ($customerStatus['message'] != null) {
            return response()->json([
                'error' => $customerStatus['message']
            ], 400);
        }
        return response()->json([
            'data' => $customerStatus
        ], 200);
    }

    public function getStatistics(int $id): JsonResponse
    {
        $statistics = $this->CustomerService->getStatistics($id);

        return response()->json([
            'data' => ($statistics)
        ], 200);
    }

    public function checkUsername($username): JsonResponse
    {
        $username = strtolower($username);
        $username = Customer::where('username', $username)->first();
        if ($username) {
            return response()->json([
                'message' => 'اسم المستخدم مأخوذ مسبقاً'
            ], 422);
        }
        return response()->json([
            'message' => 'اسم المستخدم متاح'
        ]);
    }

    public function checkEmail($email): JsonResponse
    {
        $email = strtolower($email);
        $email = Customer::where('email', $email)->first();
        if ($email) {
            return response()->json([
                'message' => 'البريد الإلكتروني مأخوذ مسبقاً'
            ], 422);
        }
        return response()->json([
            'message' => 'البريد الإلكتروني متاح'
        ]);
    }

    public function bookAnAppointmentByCustomer($appointment): AppointmentResource|JsonResponse
    {

        $appointment = Appointment::query()->where('id', '=', $appointment)->findOrFail($appointment);

        // make sure the appointment is in the future
        if ($appointment->is_future === false) {
            return response()->json([
                'message' => 'لا يمكن حجز الموعد لأنه مضى',
            ], 422);
        }

        // make sure the appointment is reserved
        if ($appointment->is_reserved === true) {
            return response()->json([
                'message' => 'لا يمكن حجز الموعد لأنه محجوز سابقاً',
            ], 422);
        }

        $appointment = $this->CustomerService->bookAnAppointmentByCustomer($appointment);
        return new AppointmentResource($appointment);

    }


    public function getCustomerAppointments(): AnonymousResourceCollection
    {
        $appointments = $this->CustomerService->getCustomerAppointments();
        return AppointmentResource::collection($appointments);
    }

    public function getCustomerInfo(): CustomerResource
    {
        $customer = $this->CustomerService->getCustomerInfo();
        return new CustomerResource($customer);
    }

    public function getCustomerStatistics(): JsonResponse
    {
        $statistics = $this->CustomerService->getCustomerStatistics();
        return response()->json([
            'data' => ($statistics)
        ]);
    }


}
