<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\JobTitleRepositoryInterface;
use App\Domain\Models\JobTitle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class EloquentJobTitleRepository implements JobTitleRepositoryInterface
{
    public function getJobTitleList(): Collection
    {
        $jobTitles = JobTitle::query()->with('permissions');

        if (request()->has('name')) {
            $name = request()->query('name');

            $name = trim($name);

            $name = strtolower($name);

            $jobTitles->whereRaw('LOWER(name) LIKE ?', ["%$name%"]);
        }

        $jobTitles = $jobTitles->get();

        $employeeRepository = new EloquentEmployeeRepository();
        foreach ($jobTitles as &$jobTitle)
            $jobTitle['employees_count']=count($employeeRepository->getEmployeeListByTitleId($jobTitle['job_title_id']));

        return $jobTitles;
    }

    public function getJobTitleById(int $id): JobTitle|Builder|null
    {
        $employeeRepository = new EloquentEmployeeRepository();
        $jobTitle = JobTitle::query()->with('permissions')->find($id);
        if($jobTitle) $jobTitle['employees_count']=count($employeeRepository->getEmployeeListByTitleId($jobTitle['job_title_id']));

        return $jobTitle;
    }

    public function createJobTitle(array $data): JobTitle|Builder|null
    {
        $jobTitle= JobTitle::query()->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        foreach ($data['permissions_ids'] as $permission) {
            $jobTitle->permissions()->attach($permission,[
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        return $jobTitle->load('permissions');
    }

    public function updateJobTitle(int $id, array $data): JobTitle|Builder|null
    {
        $jobTitle = JobTitle::query()->find($id);
        $jobTitle->name = $data['name'] ?? $jobTitle->name;
        $jobTitle->description = $data['description'] ?? $jobTitle->description;
        $jobTitle->save();
        if(array_key_exists('permissions_ids', $data)) {
            $jobTitle->permissions()->detach();
            foreach ($data['permissions_ids'] as $permission) {
                $jobTitle->permissions()->attach($permission, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        return $jobTitle->load('permissions');
    }

    public function deleteJobTitle($id): JobTitle|Builder|null
    {
        $employeeRepository = new EloquentEmployeeRepository();

        $jobTitle = JobTitle::query()->with('permissions')->find($id);
        if(!$jobTitle) return null;

        $jobTitle['employees_count']=count($employeeRepository->getEmployeeListByTitleId($jobTitle['job_title_id']));
        if($jobTitle['employees_count']>0){
            return $jobTitle;
        }
        //delete records in pivot table
        $jobTitle->permissions()->update(['deleted_at' => now()]);
        //delete the job title record
        $jobTitle->delete();

        return $jobTitle;

    }
}
