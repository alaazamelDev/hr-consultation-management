<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer education_level_id
 * @property string name
 * @property mixed pivot
 */
class EducationLevelResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'education_level_id' => $this->education_level_id,
            'name' => $this->name,
            'education_record_id' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->education_record_id;
            }),
            'univ_name' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->univ_name;
            }),
            'city' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->city;
            }),
            'start_date' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->start_date;
            }),
            'end_date' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->end_date;
            }),
            'specialize' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->specialize;
            }),
            'grade' => $this->whenPivotLoaded('education_records', function () {
                return $this->pivot->grade;
            }),
        ];
    }
}
