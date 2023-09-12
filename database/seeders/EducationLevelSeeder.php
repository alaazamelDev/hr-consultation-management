<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('education_levels')
            ->insert([
                ['name' => 'ابتدائي'],
                ['name' => 'إعدادي'],
                ['name' => 'ثانوي'],
                ['name' => 'دبلوم بعد الثانوية'],
                ['name' => 'الجامعة'],
                ['name' => 'دراسات عليا'],
            ]);
    }
}
