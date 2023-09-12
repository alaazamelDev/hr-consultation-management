<?php

namespace Database\Seeders;

use App\Domain\Models\JobVacancy;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed a new job vacancy
        JobVacancy::create([
            'name' => 'مهندس برمجيات',
            'description' => 'مطلوب مهندس برمجيات للعمل في قسم البرمجيات',
            'count' => 1,
            'vacancy_status_id' => 1,
            'dep_id' => 1,
        ]);
    }
}
