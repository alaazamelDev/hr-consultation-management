<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacancyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Job Vacancy status: (in Arabic)
        //  - open
        //  - closed
        //  - archived
        $vacancyStatuses = [
            ['name' => 'مفتوح', 'description' => 'حالة الشاغر الوظيفي: مفتوح'],
            ['name' => 'مغلق', 'description' => 'حالة الشاغر الوظيفي: مغلق'],
            ['name' => 'مؤرشف', 'description' => 'حالة الشاغر الوظيفي: مؤرشف'],
        ];
        DB::table('vacancy_statuses')
            ->insert($vacancyStatuses);
    }
}
