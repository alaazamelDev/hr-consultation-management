<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

/***
 * Class UpdateJobApplicationRequest
 * @package App\Application\Http\Requests
 *
 * have all validation rules for updating job application
 */
class UpdateJobApplicationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            //    // job application data
            "job_application" => ['sometimes'],
            "job_application.job_vacancy_id" => ['sometimes', 'integer', 'exists:job_vacancies,job_vacancy_id'],
            "job_application.section_man_notes" => ['sometimes', 'nullable', 'string'],
            "job_application.vice_man_rec" => ['sometimes', 'nullable', 'string'],
            "job_application.app_status_id" => ['sometimes', 'integer', 'exists:application_statuses,app_status_id'],


            // employee data
            "personal_data" => ['sometimes'],
            "personal_data.first_name" => ['sometimes', 'string', 'max:255'],
            "personal_data.last_name" => ['sometimes', 'string', 'max:255'],
            'personal_data.personal_photo' => ['sometimes'],
            'personal_data.father_name' => ['sometimes', 'string', 'max:255'],
            'personal_data.grand_father_name' => ['sometimes', 'string', 'max:255'],
            'personal_data.birth_date' => ['sometimes', 'date'],
            'personal_data.birth_place' => ['sometimes', 'string', 'max:80'],
            'personal_data.marital_status' => ['sometimes', 'integer'],

            // job data
            'job_data' => ['sometimes'],
            'job_data.start_working_date' => ['sometimes', 'date'],
            'job_data.is_employed' => ['sometimes', 'boolean'],


            // personal card data
            "personal_card" => ['sometimes'],
            "personal_card.card_number" => ['sometimes', 'string', 'max:25'],
            "personal_card.card_place_of_issue" => ['sometimes', 'string', 'max:80'],
            "personal_card.card_date_of_issue" => ['sometimes', 'date'],

            // passport data
            "passport" => ['sometimes', 'nullable'],
            'passport.passport_number' => ['string', 'max:25'],
            'passport.passport_place_of_issue' => ['string', 'max:80'],
            'passport.passport_date_of_issue' => ['date'],


            // address data
            "address" => ['sometimes'],
            "address.state" => ['sometimes', 'string', 'max:50'],
            "address.city" => ['sometimes', 'string', 'max:50'],
            "address.street" => ['sometimes', 'string', 'max:70'],
            "address.postal_code" => ["sometimes", 'nullable', 'string', 'max:10'],
            "address.email" => ["sometimes", 'nullable', 'email', 'max:255'],
            "address.mobile_no" => ["sometimes", 'nullable', 'string', 'max:25'],
            "address.home_phone_no" => ["sometimes", 'nullable', 'string', 'max:25'],
            "address.work_phone_no" => ["sometimes", 'nullable', 'string', 'max:25'],

            // driving licence data
            "driving_licence" => ['sometimes', 'nullable'],
            "driving_licence.category" => ['sometimes', 'nullable', 'string', 'max:50'],
            "driving_licence.number" => ['string', 'max:25'],
            "driving_licence.date_of_issue" => ['date'],
            "driving_licence.expiry_date" => ['date'],
            'driving_licence.place_of_issue' => ['string', 'max:80'],
            "driving_licence.blood_group" => ['string', 'max:25'],

            // dependents
            "dependents" => ['sometimes', 'nullable', 'array'],
            "dependents.*.dependent_id" => ['sometimes', 'integer', 'exists:dependents,dependent_id'],
            "dependents.*.name" => ['sometimes', 'string', 'max:255'],
            "dependents.*.age" => ['sometimes', 'integer'],
            "dependents.*.relationship" => ['sometimes', 'string', 'max:255'],
            "dependents.*.address" => ['sometimes', 'string', 'max:255'],


            // previous employment record
            "previous_employment_record" => ['sometimes', 'nullable', 'array'],
            "previous_employment_record.*.prev_emp_record_id" => ['sometimes', 'integer', 'exists:previous_employment_records,prev_emp_record_id'],
            "previous_employment_record.*.employer_name" => ['sometimes', 'string', 'max:255'],
            "previous_employment_record.*.address" => ['sometimes', 'string', 'max:255'],
            "previous_employment_record.*.telephone" => ['sometimes', 'string', 'max:25'],
            "previous_employment_record.*.job_title" => ['sometimes', 'string', 'max:255'],
            "previous_employment_record.*.job_description" => ['sometimes', 'string'],
            "previous_employment_record.*.start_date" => ['sometimes', 'date'],
            "previous_employment_record.*.end_date" => ['sometimes', 'date'],
            "previous_employment_record.*.salary" => ['sometimes', 'integer'],
            "previous_employment_record.*.allowance" => ['sometimes', 'integer'],
            "previous_employment_record.*.quit_reason" => ['sometimes', 'nullable', 'string', 'max:255'],


            // convictions
            "convictions" => ['sometimes', 'nullable', 'array'],
            "convictions.*.conviction_id" => ['sometimes', 'integer', 'exists:convictions,conviction_id'],
            "convictions.*.description" => ['sometimes', 'string'],

            // education
            "education" => ['sometimes', 'nullable', 'array'],
            "education.*.univ_name" => ['sometimes', 'string', 'max:255'],
            "education.*.city" => ['sometimes', 'string', 'max:255'],
            "education.*.start_date" => ['sometimes', 'date'],
            "education.*.end_date" => ['sometimes', 'date'],
            "education.*.specialize" => ['sometimes', 'nullable', 'string', 'max:255'],
            "education.*.grade" => ['sometimes', 'nullable', 'numeric'],
            "education.*.education_level_id" => ['required', 'integer', 'exists:education_levels,education_level_id'],

            // training courses
            "training_courses" => ['sometimes', 'nullable', 'array'],
            "training_courses.*.training_course_id" => ['sometimes', 'integer', 'exists:training_courses,training_course_id'],
            "training_courses.*.course_name" => ['sometimes', 'string', 'max:255'],
            "training_courses.*.institute_name" => ['sometimes', 'string', 'max:255'],
            "training_courses.*.city" => ['sometimes', 'string', 'max:255'],
            "training_courses.*.start_date" => ['sometimes', 'date'],
            "training_courses.*.end_date" => ['sometimes', 'date'],
            "training_courses.*.specialize" => ['sometimes', 'string', 'max:255'],

            // skills
            "skills" => ['sometimes', 'nullable', 'array'],
            "skills.*.skill_name" => ['required', 'string', 'max:255'],

            // languages
            "languages" => ['sometimes', 'nullable', 'array'],
            "languages.*.language_name" => ['required', 'string', 'max:255'],
            "languages.*.reading" => ['sometimes', 'nullable', 'integer', 'max:3'],
            "languages.*.writing" => ['sometimes', 'nullable', 'integer', 'max:3'],
            "languages.*.speaking" => ['sometimes', 'nullable', 'integer', 'max:3'],

            // computer skills
            "computer_skills" => ['sometimes', 'nullable', 'array'],
            "computer_skills.*.skill_name" => ['sometimes', 'string', 'max:255'],
            "computer_skills.*.level" => ['sometimes', 'integer', 'max:3'],

            // relatives (from center employees)
            "relatives" => ['sometimes', 'nullable', 'array'],
            "relatives.*.relative_data_id" => ['sometimes', 'integer', 'exists:employees,emp_id'],

            // references
            "references" => ['sometimes', 'nullable', 'array'],
            "references.*.reference_id" => ['sometimes', 'integer', 'exists:references,reference_id'],
            "references.*.name" => ['sometimes', 'string', 'max:70'],
            "references.*.job" => ['sometimes', 'string', 'max:70'],
            "references.*.company" => ['sometimes', 'string', 'max:70'],
            "references.*.telephone" => ['sometimes', 'string', 'max:25'],
            "references.*.address" => ['sometimes', 'string'],

            // certificates
            "certificates" => ['sometimes', 'nullable', 'array'],
            "certificates.*.certificate_id" => ['sometimes', 'integer', 'exists:certificates,certificate_id'],
            "certificates.*.certificate_name" => ['sometimes', 'string', 'max:255'],
            // this can be either string or file
            "certificates.*.file" => ['sometimes'],

            // here we will add arrays of ids to be deleted

            // dependents
            "deleted_dependents" => ['sometimes', 'nullable', 'array'],
            "deleted_dependents.*" => ['sometimes', 'integer', 'exists:dependents,dependent_id'],

            // previous employment record
            "deleted_previous_employment_record" => ['sometimes', 'nullable', 'array'],
            "deleted_previous_employment_record.*" => ['sometimes', 'integer', 'exists:previous_employment_records,prev_emp_record_id'],

            // convictions
            "deleted_convictions" => ['sometimes', 'nullable', 'array'],
            "deleted_convictions.*" => ['sometimes', 'integer', 'exists:convictions,conviction_id'],

            // education
            "deleted_education" => ['sometimes', 'nullable', 'array'],
            "deleted_education.*" => ['sometimes', 'integer'],

            // training courses
            "deleted_training_courses" => ['sometimes', 'nullable', 'array'],
            "deleted_training_courses.*" => ['sometimes', 'integer', 'exists:training_courses,training_course_id'],

            // skills
            "deleted_skills" => ['sometimes', 'nullable', 'array'],
            "deleted_skills.*" => ['sometimes', 'integer', 'exists:skills,skill_id'],

            // languages
            "deleted_languages" => ['sometimes', 'nullable', 'array'],
            "deleted_languages.*" => ['sometimes', 'integer', 'exists:languages,language_id'],

            // computer skills
            "deleted_computer_skills" => ['sometimes', 'nullable', 'array'],
            "deleted_computer_skills.*" => ['sometimes', 'integer', 'exists:computer_skills,computer_skill_id'],

//            // relatives
            "deleted_relatives" => ['sometimes', 'nullable', 'array'],
            "deleted_relatives.*" => ['sometimes', 'integer', 'exists:relatives,relative_id'],

            // references
            "deleted_references" => ['sometimes', 'nullable', 'array'],
            "deleted_references.*" => ['sometimes', 'integer', 'exists:references,reference_id'],

            // certificates
            "deleted_certificates" => ['sometimes', 'nullable', 'array'],
            "deleted_certificates.*" => ['sometimes', 'integer', 'exists:certificates,certificate_id'],
        ];
    }
}
