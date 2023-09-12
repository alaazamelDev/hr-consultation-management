<?php

namespace Database\Seeders;

use App\Domain\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userTypes = [
            // employee
            [
                'name' => 'موظف',
                'description' => 'موظف يعمل ضمن المركز',
            ],
            // consultant
            [
                'name' => 'مستشار',
                'description' => 'مستشار يعمل ضمن المركز',
            ],
        ];

        foreach ($userTypes as $userType) {
            UserType::query()->create($userType);
        }
    }
}
