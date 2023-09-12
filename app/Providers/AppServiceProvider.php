<?php

namespace App\Providers;

use App\Domain\Models\Absence;
use App\Domain\Models\Attendance;
use App\Domain\Models\Employee;
use App\Domain\Models\JobApplication;
use App\Domain\Models\JobTitle;
use App\Domain\Models\JobTitlePermission;
use App\Domain\Models\Leave;
use App\Domain\Models\ShiftRequest;
use App\Domain\Models\Staffing;
use App\Domain\Models\StaffPermission;
use App\Domain\Models\VacationRequest;
use App\Domain\Repositories\AbsenceRepositoryInterface;
use App\Domain\Repositories\AppointmentRepositoryInterface;
use App\Domain\Repositories\AttendanceRepositoryInterface;

use App\Domain\Repositories\AuthenticationRepositoryInterface;
use App\Domain\Repositories\CaseNoteRepositoryInterface;
use App\Domain\Repositories\ClinicRepositoryInterface;
use App\Domain\Repositories\ConsultantRepositoryInterface;
use App\Domain\Repositories\CustomerRepositoryInterface;
use App\Domain\Repositories\DepartmentRepositoryInterface;
use App\Domain\Repositories\EducationLevelRepositoryInterface;
use App\Domain\Repositories\EmployeeRepositoryInterface;
use App\Domain\Repositories\EmployeeVacationRepositoryInterface;
use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Repositories\FingerDeviceRepositoryInterface;
use App\Domain\Repositories\HolidayRepositoryInterface;
use App\Domain\Repositories\JobTitleRepositoryInterface;
use App\Domain\Repositories\JobApplicationRepositoryInterface;
use App\Domain\Repositories\JobVacancyRepositoryInterface;
use App\Domain\Repositories\LeaveRepositoryInterface;
use App\Domain\Repositories\LogRepositoryInterface;
use App\Domain\Repositories\PermissionRepositoryInterface;
use App\Domain\Repositories\ScheduleRepositoryInterface;
use App\Domain\Repositories\TimeSheetRepositoryInterface;
use App\Domain\Repositories\UnRegisteredAccountRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Repositories\WorkingDayRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\EloquentAbsenceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAppointmentRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentAttendanceRepository;
use App\Domain\Repositories\ShiftRequestRepositoryInterface;
use App\Domain\Repositories\VacationRequestRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\EloquentAuthenticationRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentCaseNoteRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentClinicRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentConsultantRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentCustomerRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDepartmentRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEducationLevelRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEmployeeVacationRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentEventRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentFingerDeviceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentHolidayRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentJobApplicationRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentJobTitleRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentJobVacancyRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentLeaveRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentLogRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentPermissionRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentScheduleRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentTimeSheetRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUnRegisteredAccountRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentWorkingDayRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentShiftRequestRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentVacationRequestRepository;
use App\Observers\AbsenceObserver;
use App\Observers\AttendanceObserver;
use App\Observers\EmployeeObserver;
use App\Observers\JobApplicationObserver;
use App\Observers\JobTitleObserver;
use App\Observers\JobTitlePermissionObserver;
use App\Observers\LeaveObserver;
use App\Observers\ShiftRequestObserver;
use App\Observers\StaffingObserver;
use App\Observers\StaffPermissionObserver;
use App\Observers\VacationRequestObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the repository interface with its implementation
        $this->app->bind(JobApplicationRepositoryInterface::class, EloquentJobApplicationRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, EloquentDepartmentRepository::class);
        $this->app->bind(JobVacancyRepositoryInterface::class, EloquentJobVacancyRepository::class);
        $this->app->bind(ShiftRequestRepositoryInterface::class, EloquentShiftRequestRepository::class);
        $this->app->bind(VacationRequestRepositoryInterface::class, EloquentVacationRequestRepository::class);
        $this->app->bind(JobTitleRepositoryInterface::class, EloquentJobTitleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, EloquentPermissionRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EloquentEmployeeRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, EloquentScheduleRepository::class);
        $this->app->bind(EmployeeRepositoryInterface::class, EloquentEmployeeRepository::class);
        $this->app->bind(FingerDeviceRepositoryInterface::class, EloquentFingerDeviceRepository::class);
        $this->app->bind(AttendanceRepositoryInterface::class, EloquentAttendanceRepository::class);
        $this->app->bind(LeaveRepositoryInterface::class, EloquentLeaveRepository::class);
        $this->app->bind(WorkingDayRepositoryInterface::class, EloquentWorkingDayRepository::class);
        $this->app->bind(HolidayRepositoryInterface::class, EloquentHolidayRepository::class);
        $this->app->bind(EmployeeVacationRepositoryInterface::class, EloquentEmployeeVacationRepository::class);
        $this->app->bind(LogRepositoryInterface::class, EloquentLogRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(AbsenceRepositoryInterface::class, EloquentAbsenceRepository::class);
        $this->app->bind(AuthenticationRepositoryInterface::class, EloquentAuthenticationRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(ConsultantRepositoryInterface::class, EloquentConsultantRepository::class);
        $this->app->bind(ClinicRepositoryInterface::class, EloquentClinicRepository::class);
        $this->app->bind(EducationLevelRepositoryInterface::class, EloquentEducationLevelRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EloquentEventRepository::class);
        $this->app->bind(TimeSheetRepositoryInterface::class, EloquentTimeSheetRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, EloquentAppointmentRepository::class);
        $this->app->bind(UnRegisteredAccountRepositoryInterface::class, EloquentUnRegisteredAccountRepository::class);
        $this->app->bind(CaseNoteRepositoryInterface::class,EloquentCaseNoteRepository ::class);
    }


    public function boot(): void
    {
        Absence::observe(AbsenceObserver::class);
        Attendance::observe(AttendanceObserver::class);
        Employee::observe(EmployeeObserver::class);
        JobApplication::observe(JobApplicationObserver::class);
        JobTitle::observe(JobTitleObserver::class);
        JobTitlePermission::observe(JobTitlePermissionObserver::class);
        Leave::observe(LeaveObserver::class);
        ShiftRequest::observe(ShiftRequestObserver::class);
        Staffing::observe(StaffingObserver::class);
        StaffPermission::observe(StaffPermissionObserver::class);
        VacationRequest::observe(VacationRequestObserver::class);
    }
}
