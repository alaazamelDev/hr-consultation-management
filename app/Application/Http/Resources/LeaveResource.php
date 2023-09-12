<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (isset($this->resource['data'])) {
            $data = $this->resource;
            $i = 0;
            $result = null;
            foreach ($this->resource['data'] as $key => $value) {
                $result[$i]["leave_id"] = $value["leave_id"];
                $result[$i]["leave_emp_id"] = $value["emp_id"];
                $result[$i]["leave_status"] = $value["status"];
                $result[$i]["leave_state"] = $value["state"];
                $result[$i]["leave_time"] = $value["leave_time"];
                $result[$i]["leave_date"] = $value["leave_date"];
                $result[$i]["leaveBefore"] = $value["leaveBefore"] ?? null;
                $result[$i]["deleted_at"] = $value["deleted_at"] ?? null;
                $result[$i]["employee"] = [
                    "full_name" => $value["employee"]["job_application"]["emp_data"]["first_name"].' '.$value["employee"]["job_application"]["emp_data"]["last_name"],
                    "cur_dep" => $value["employee"]["cur_dep"],
                    "schedule" => [
                        "schedule_id" => $value["employee"]["schedule"]["schedule_id"],
                        "name" => $value["employee"]["schedule"]["name"],
                        "time_in" => $value["employee"]["schedule"]["time_in"],
                        "time_out" => $value["employee"]["schedule"]["time_out"],
                    ],

                ];
                $i++;
            }
            $data['data'] =$result;

            return $data;
        }
        else {
            return [
                "leave_id" => $this->resource["leave_id"],
                "leave_emp_id" => $this->resource["emp_id"],
                "leave_status" => $this->resource["status"],
                "leave_state" => $this->resource["state"],
                "leave_time" => $this->resource["leave_time"],
                "leave_date" => $this->resource["leave_date"],
                "leaveBefore" => $this->resource["leaveBefore"] ?? null ,
                "deleted_at" => $this->resource["deleted_at"] ?? null,

                "employee" => [
                "full_name" => $this->resource["employee"]["empdata"]["first_name"] .' '. $this->resource["employee"]["empdata"]["last_name"],
                "cur_dep" => $this->resource["employee"]["cur_dep"],
                "schedule" => [
                    "schedule_id" => $this->resource["employee"]["schedule"]["schedule_id"],
                    "name" => $this->resource["employee"]["schedule"]["name"],
                    "time_in" => $this->resource["employee"]["schedule"]["time_in"],
                    "time_out" => $this->resource["employee"]["schedule"]["time_out"],
                ],
            ]

            ];
        }
    }
}
