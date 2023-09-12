<?php


namespace App\Application\Http\Resources;

use App\Domain\Models\EmploymentStatus;
use App\Domain\Models\JobApplication;
use App\Domain\Models\Staffing;
use App\Domain\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

/**
 * @property User user
 * @property integer emp_id
 * @property JobApplication jobApplication
 * @property Staffing[] staffings
 * @property EmploymentStatus employmentStatus
 * @property mixed current_employment_status
 * @property mixed current_department
 * @property mixed current_job_title
 * @property mixed start_working_date
 * @property mixed full_name
 * @property mixed schedule
 */
class EmployeeDetailsResource extends JsonResource
{
    #[Pure] public function toArray($request): array
    {
        return [

            // employee data
            'emp_id' => $this->emp_id,

            // user data
            'email' => $this->user->email,
            'username' => $this->user->username,
            'start_working_date' => $this->start_working_date,

            // job application data
            'job_application' => new JobApplicationDetailsResource($this->jobApplication),

            // schedule data
            'schedule' => new ScheduleResource($this->schedule),

            'current_employment_status' => new EmploymentStatusResource($this->current_employment_status),

            // job title data
            'current_job_title' => $this->current_job_title
                ? [
                    'job_title_id' => $this->current_job_title->job_title_id,
                    'name' => $this->current_job_title->name,
                    'description' => $this->current_job_title->description,
                    'employees_count' => $this->current_job_title->employees_count,
                ]
                : null,

            'current_department' => $this->current_department
                ? new DepartmentResource($this->current_department)
                : null,

            'permissions' => EmployeePermissionResource::collection($this->permissions),
            //  attendance (full information) (for later)
            // TODO: add attendance information
        ];
    }

}
