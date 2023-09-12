<?php


namespace App\Application\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreJobApplicationRequest extends FormRequest
{
    // validation rules
    public function rules(): array
    {
        return [
            // job application data
            "job_application" => ['required'],
            "job_application.job_vacancy_id" => ['required', 'integer', 'exists:job_vacancies,job_vacancy_id'],
            "job_application.section_man_notes" => ['sometimes', 'nullable', 'string'],
            "job_application.vice_man_rec" => ['sometimes', 'nullable', 'string'],


            // employee data
            "personal_data" => ['required'],
            "personal_data.first_name" => ['required', 'string', 'max:255'],
            "personal_data.last_name" => ['required', 'string', 'max:255'],
            'personal_data.personal_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'personal_data.father_name' => ['required', 'string', 'max:255'],
            'personal_data.grand_father_name' => ['required', 'string', 'max:255'],
            'personal_data.birth_date' => ['required', 'date'],
            'personal_data.birth_place' => ['required', 'string', 'max:80'],
            'personal_data.marital_status' => ['required', 'integer'],

            // job data
            'job_data' => ['required'],
            'job_data.start_working_date' => ['required', 'date'],
            'job_data.is_employed' => ['required', 'boolean'],


            // personal card data
            "personal_card" => ['required'],
            "personal_card.card_number" => ['required', 'string', 'max:25'],
            "personal_card.card_place_of_issue" => ['required', 'string', 'max:80'],
            "personal_card.card_date_of_issue" => ['required', 'date'],

            // passport data
            "passport" => ['sometimes', 'nullable'],
            'passport.passport_number' => ['required_with:passport', 'string', 'max:25'],
            'passport.passport_place_of_issue' => ['required_with:passport', 'string', 'max:80'],
            'passport.passport_date_of_issue' => ['required_with:passport', 'date'],


            // address data
            "address" => ['required'],
            "address.state" => ['required', 'string', 'max:50'],
            "address.city" => ['required', 'string', 'max:50'],
            "address.street" => ['required', 'string', 'max:70'],
            "address.postal_code" => ["sometimes", 'nullable', 'string', 'max:10'],
            "address.email" => ["sometimes", 'nullable', 'email', 'max:255'],
            "address.mobile_no" => ["sometimes", 'nullable', 'string', 'max:25'],
            "address.home_phone_no" => ["sometimes", 'nullable', 'string', 'max:25'],
            "address.work_phone_no" => ["sometimes", 'nullable', 'string', 'max:25'],

            // driving licence data
            "driving_licence" => ['sometimes', 'nullable'],
            "driving_licence.category" => ['sometimes', 'nullable', 'string', 'max:50'],
            "driving_licence.number" => ['required_with:driving_licence', 'string', 'max:25'],
            "driving_licence.date_of_issue" => ['required_with:driving_licence', 'date'],
            "driving_licence.expiry_date" => ['required_with:driving_licence', 'date'],
            'driving_licence.place_of_issue' => ['required_with:driving_licence', 'string', 'max:80'],
            "driving_licence.blood_group" => ['required_with:driving_licence', 'string', 'max:25'],

            // dependents
            "dependents" => ['sometimes', 'nullable', 'array'],
            "dependents.*.name" => ['required', 'string', 'max:255'],
            "dependents.*.age" => ['required', 'integer'],
            "dependents.*.relationship" => ['required', 'string', 'max:255'],
            "dependents.*.address" => ['required', 'string', 'max:255'],


            // previous employment record
            "previous_employment_record" => ['sometimes', 'nullable', 'array'],
            "previous_employment_record.*.employer_name" => ['required', 'string', 'max:255'],
            "previous_employment_record.*.address" => ['required', 'string', 'max:255'],
            "previous_employment_record.*.telephone" => ['required', 'string', 'max:25'],
            "previous_employment_record.*.job_title" => ['required', 'string', 'max:255'],
            "previous_employment_record.*.job_description" => ['required', 'string'],
            "previous_employment_record.*.start_date" => ['required', 'date'],
            "previous_employment_record.*.end_date" => ['required', 'date'],
            "previous_employment_record.*.salary" => ['required', 'integer'],
            "previous_employment_record.*.allowance" => ['required', 'integer'],
            "previous_employment_record.*.quit_reason" => ['sometimes', 'nullable', 'string', 'max:255'],


            // convictions
            "convictions" => ['sometimes', 'nullable', 'array'],
            "convictions.*.description" => ['required', 'string'],

            // education
            "education" => ['sometimes', 'nullable', 'array'],
            "education.*.univ_name" => ['required', 'string', 'max:255'],
            "education.*.city" => ['required', 'string', 'max:255'],
            "education.*.start_date" => ['required', 'date'],
            "education.*.end_date" => ['required', 'date'],
            "education.*.specialize" => ['sometimes', 'nullable', 'string', 'max:255'],
            "education.*.grade" => ['sometimes', 'nullable', 'numeric'],
            "education.*.education_level_id" => ['required', 'integer', 'exists:education_levels,education_level_id'],

            // training courses
            "training_courses" => ['sometimes', 'nullable', 'array'],
            "training_courses.*.course_name" => ['required', 'string', 'max:255'],
            "training_courses.*.institute_name" => ['required', 'string', 'max:255'],
            "training_courses.*.city" => ['required', 'string', 'max:255'],
            "training_courses.*.start_date" => ['required', 'date'],
            "training_courses.*.end_date" => ['required', 'date'],
            "training_courses.*.specialize" => ['required', 'string', 'max:255'],

            // skills
            "skills" => ['sometimes', 'nullable', 'array'],
            "skills.*.skill_name" => ['required', 'string', 'max:255'],

            // languages
            "languages" => ['sometimes', 'nullable', 'array'],
            "languages.*.language_name" => ['required', 'string', 'max:255'],
            "languages.*.reading" => ['required', 'integer', 'max:3'],
            "languages.*.writing" => ['required', 'integer', 'max:3'],
            "languages.*.speaking" => ['required', 'integer', 'max:3'],

            // computer skills
            "computer_skills" => ['sometimes', 'nullable', 'array'],
            "computer_skills.*.skill_name" => ['required', 'string', 'max:255'],
            "computer_skills.*.level" => ['required', 'integer', 'max:3'],

            // relatives (from center employees)
            "relatives" => ['sometimes', 'nullable', 'array'],
            "relatives.*.relative_data_id" => ['required', 'integer', 'exists:emp_data,emp_data_id'],

            // references
            "references" => ['sometimes', 'nullable', 'array'],
            "references.*.name" => ['required', 'string', 'max:70'],
            "references.*.job" => ['required', 'string', 'max:70'],
            "references.*.company" => ['required', 'string', 'max:70'],
            "references.*.telephone" => ['required', 'string', 'max:25'],
            "references.*.address" => ['required', 'string'],

            // certificates
            "certificates" => ['sometimes', 'nullable', 'array'],
            "certificates.*.certificate_name" => ['required', 'string', 'max:255'],
            "certificates.*.file" => ['required', 'file', 'mimes:pdf,doc,docx,jpeg,png,jpg'],
        ];
    }

}
