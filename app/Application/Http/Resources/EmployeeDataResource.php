<?php


namespace App\Application\Http\Resources;

use App\Domain\Models\Address;
use App\Domain\Models\DrivingLicence;
use App\Domain\Models\Passport;
use App\Domain\Models\PersonalCard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property string first_name
 * @property string last_name
 * @property string full_name
 * @property string personal_photo
 * @property string father_name
 * @property string grand_father_name
 * @property mixed birth_date
 * @property string birth_place
 * @property integer marital_status
 * @property mixed start_working_date
 * @property boolean is_employed
 * @property PersonalCard personalCard
 * @property Passport passport
 * @property Address address
 * @property DrivingLicence drivingLicence
 * @property mixed dependents
 * @property mixed previousEmploymentRecords
 * @property mixed convictions
 * @property mixed educationLevels
 * @property mixed trainingCourses
 * @property mixed skills
 * @property mixed languages
 * @property mixed computerSkills
 * @property mixed relatives
 * @property mixed references
 * @property mixed certificates
 */
class EmployeeDataResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            // Personal data
            'personal_data' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'full_name' => $this->full_name,
                'personal_photo' => $this->personal_photo ? Storage::url($this->personal_photo) : null,
                'father_name' => $this->father_name,
                'grand_father_name' => $this->grand_father_name,
                'birth_date' => $this->birth_date,
                'birth_place' => $this->birth_place,
                'marital_status' => $this->marital_status,
            ],

            // Job Data
            'job_data' => [
                'start_working_date' => $this->start_working_date,
                'is_employed' => $this->is_employed,
            ],

            // Personal Card data
            "personal_card" => new PersonalCardResource($this->personalCard),

            // Passport data
            "passport" => isset($this->passport)
                ? new PassportResource($this->passport)
                : null,

            // Address data
            "address" => new AddressResource($this->address),

            // Driving License data (if exists)
            "driving_licence" => isset($this->drivingLicence)
                ? new DrivingLicenseResource($this->drivingLicence)
                : null,

            // Dependents data (if exists)
            "dependents" => DependentResource::collection($this->dependents),

            // Previous Employment Records data (if exists)
            "previous_employment_record" => PreviousEmploymentRecordResource::collection($this->previousEmploymentRecords),

            // Convictions data (if exists)
            "convictions" => ConvictionResource::collection($this->convictions),

            // Education Levels data (if exists)
            "education" => EducationLevelResource::collection($this->educationLevels),

            // Training Courses data (if exists)
            "training_courses" => TrainingCourseResource::collection($this->trainingCourses),

            // Skills data (if exists)
            "skills" => SkillResource::collection($this->skills),

            // Languages data (if exists)
            "languages" => LanguageResource::collection($this->languages),

            // Computer Skills data (if exists)
            "computer_skills" => ComputerSkillResource::collection($this->computerSkills),

            // Relatives (that represents employees) data (if exists) TODO: FIX IT
            "relatives" => /*$this->relatives ? RelativeResource::collection($this->relatives) : null*/[],

            // References data (if exists)
            "references" => ReferenceResource::collection($this->references),

            // Certificates data (if exists)
            "certificates" => CertificateResource::collection($this->certificates),
        ];
    }
}
