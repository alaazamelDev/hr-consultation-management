<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\EmployeeVacation;

class EmployeeVacationResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        if(!isset($this['employee'])){
            return [
                'employee_vacation_id' =>$this['employee_vacation_id'],
                'emp_id' => $this['emp_id'],
                'start_date' => $this['start_date'],
                'total_days' => $this['total_days'],
                'remaining_days' => $this['remaining_days'],
                ];
        }
        return [
            'employee_vacation_id' =>$this['employee_vacation_id'],
            'emp_id' => $this['emp_id'],
            'start_date' => $this['start_date'],
            'total_days' => $this['total_days'],
            'remaining_days' => $this['remaining_days'],
            'employee' => [
                'emp_id' => $this['employee']["emp_id"],
                'user_id' => $this['employee']["user_id"],
                'full_name' => $this['employee']["full_name"],
                'cur_dep' => $this['employee']["cur_dep"] ?? null,
                'cur_title' => $this['employee']["cur_title"] ?? null,
            ],
        ];
    }
}
