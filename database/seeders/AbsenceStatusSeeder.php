<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsenceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
         * 1- غياب مبرر
         * 2- غياب غير مبرر
         */
        DB::table('absence_statuses')
            ->insert([
                ['name' => 'غياب مبرر', 'description' => 'غياب مبرر لايحاسب عليه','created_at' => now()],
                ['name' => 'غياب غير مبرر', 'description' => 'غياب غير مبرر يحاسب عليه','created_at' => now()],
            ]);
    }
}
