<?php

namespace App\Domain\Services;

use App\Application\Http\Resources\EventResource;
use App\Infrastructure\Persistence\Eloquent\EloquentAppointmentRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentCustomerRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDepartmentRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEventRepository;
use Carbon\Carbon;

class DashboardService
{
    public function dashboard(): array
    {
        $customerService = new CustomerService(new EloquentCustomerRepository());

        $customers = $customerService->getCustomerList()->getCollection();
        $verifiedCustomers = $customers->where('verified','=',true);
        $verifiedUsingAppCustomers = $verifiedCustomers->where('isUsingApp','=',1);
        $usingAppCustomers = $customers->where('isUsingApp','=',1);

        $employeeService = new EmployeeService(new EloquentEmployeeRepository());

        $employees = $employeeService->getEmployeeList()->getCollection();
        $workingEmployees = 0;
        foreach ($employees as $employee) {
            $status = $employee->getCurrentEmploymentStatusAttribute()->emp_status_id;
            if($status == 1)
                $workingEmployees++;
        }

        $departmentService = new DepartmentService(new EloquentDepartmentRepository());

        $departments = $departmentService->getList()->map(function ($department) {
            return [
              'dep_id' => $department['dep_id'],
              'name' => $department['name'],
              'employees_count' => $department['employees_count'],
            ];
        });

        $eventsService = new EventService(new EloquentEventRepository());

        $events = $eventsService->getEventList()->getCollection();
        $last_3_events = $events->take(3);


        // second

        $newUsingAppCustomers = $customers->where('isUsingApp','=',1)
            ->sortByDesc('created_at')->values();

        $newAddByEmployeeCustomers = $customers->where('verified','=',true)
            ->where('isUsingApp','=',0)
            ->sortByDesc('created_at')->values();

        $verifiedCustomers = $customers->where('verified','=',true);

        $appointmentService = new AppointmentService(new EloquentAppointmentRepository());
        $appointments = $appointmentService->getAppointmentList();

        $missedAppointmentsCount = $appointments->whereIn('status_id',[1,7])->count();

        $statusCounts = [];
        $statusAppointments = $appointments->groupBy('status_id');
        $statuses = [
            '',
            'تم الإلغاء بواسطة المراجع',
            'تم الإلغاء بواسطة الموظف',
            'تم الإلغاء بواسطة المستشار',
            'مكتمل',
            'محجوز',
            'متاح',
            'الموعد فائت من قبل المراجع',
            'الموعد فائت من قبل المستشار',
            'الموعد محجوز عن طريق الهاتف',
        ];
        for($i=1 ; $i<count($statuses) ; $i++){
            $statusCounts[$i]['status_name'] = $statuses[$i];
            $statusCounts[$i]['count'] = count($statusAppointments[$i] ?? []);
        }

        if (request()->has('start_date') && request()->has('end_date')) {
            $start_date = Carbon::parse(request()->input('start_date'))->toDateTimeString();
            $end_date = Carbon::parse(request()->input('end_date'))->addDay()->toDateTimeString();

            $newUsingAppCustomers = $newUsingAppCustomers->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);

            $newAddByEmployeeCustomers = $newAddByEmployeeCustomers->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);

            $verifiedCustomers = $verifiedCustomers->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);

            $events = $events->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);

            $statusCounts = [];
            $statusAppointments = $appointments->groupBy('status_id');
            for($i=1 ; $i<count($statuses) ; $i++){
                $statusCounts[$i]['status_name'] = $statuses[$i];
                $statusCounts[$i]['count'] = count($statusAppointments[$i] ?? []);
            }
        }

        return [
            'totalVerifiedCustomers' => $verifiedCustomers->count(),
            'customers' => $customers->count(),
            'verifiedUsingAppCustomers' => $verifiedUsingAppCustomers->count(),
            'usingAppCustomers' => $usingAppCustomers->count(),
            'employees' => $employees->count(),
            'workingEmployees' => $workingEmployees,
            'departments' => $departments,
            'last_3_events' => EventResource::collection($last_3_events),

            'newUsingAppCustomers' => $newUsingAppCustomers->count(),
            'newAddByEmployeeCustomers' => $newAddByEmployeeCustomers->count(),
            'verifiedCustomers' => $verifiedCustomers->count(),
            'events' => $events->count(),
            'missedAppointmentCount' => $missedAppointmentsCount,
            'appointments' => $appointments->count(),
            'statusCounts' => $statusCounts,
        ];

    }
}
