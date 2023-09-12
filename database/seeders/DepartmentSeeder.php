<?php

namespace Database\Seeders;

use App\Domain\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed a new department (in Arabic)
        Department::query()->create([
            'name' => 'الموارد البشرية',
            'description' => 'إدارة الموارد البشرية',
        ]);

    }
}
