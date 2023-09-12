<?php

namespace Database\Seeders;

use App\Domain\Models\CD\Clinic;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed clinics
        $names = ['العيادة النفسيّة', 'العيادة القانونيّة', 'العيادة الأسريّة', 'العيادة التربويّة'];
        foreach ($names as $name) {
            Clinic::create(['name' => $name]);
        }
    }
}
