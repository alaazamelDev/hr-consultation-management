<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Employment status: (in Arabic)
        //    1- Working
        //    2- Vacation
        //    3- Resigned
        //    4- Temporary-Suspention

        $employmentStatuses = [
            ['name' => 'يعمل', 'description' => 'حالة الموظف: يعمل'],
            ['name' => 'اجازة', 'description' => 'حالة الموظف: اجازة'],
            ['name' => 'استقالة', 'description' => 'حالة الموظف: استقالة'],
            ['name' => 'إيقاف مؤقت', 'description' => 'حالة الموظف: إيقاف مؤقت'],
        ];
        DB::table('employment_statuses')
            ->insert($employmentStatuses);
    }
}
