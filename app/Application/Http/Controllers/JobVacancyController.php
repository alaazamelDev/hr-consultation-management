<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Resources\JobVacancyResource;
use App\Domain\Services\JobVacancyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class JobVacancyController extends Controller
{
    private JobVacancyService $JobVacancyService;

    public function __construct(JobVacancyService $JobVacancyService)
    {
        $this->JobVacancyService = $JobVacancyService;
    }

    public function index(): AnonymousResourceCollection
    {
        $jobVacancies = $this->JobVacancyService->getJobVacancyList();
        return JobVacancyResource::collection($jobVacancies);
    }

    public function show(int $id): JsonResponse
    {
        $jobVacancy = $this->JobVacancyService->getJobVacancyById($id);
        if (!$jobVacancy) {
            return response()->json(
                ['message' => 'Job Vacancy not found'],
                404
            );
        }
        return response()->json([
            'data' => new JobVacancyResource($jobVacancy)
        ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'dep_id' => ['required', 'integer', 'exists:departments,dep_id'],
            'name' => ['required', 'string', 'max:50', 'unique:job_vacancies,name'],
            'description' => ['required', 'max:255', 'string', 'nullable'],
            'count' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => $errors
            ], 400);
        }

        $jobVacancy = $this->JobVacancyService->createJobVacancy(request()->all());
        return response()->json([
            'data' => new JobVacancyResource($jobVacancy)
        ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['string', 'max:50', 'unique:job_vacancies,name,' . $id . ',job_vacancy_id'],
            'description' => ['string', 'max:255', 'nullable'],
            'count' => ['integer'],
            'vacancy_status_id' => ['integer', 'exists:vacancy_statuses,vacancy_status_id'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => $errors
            ], 400);
        }
        $jobVacancy = $this->JobVacancyService->getJobVacancyById($id);
        if (!$jobVacancy) {
            return response()->json(
                ['message' => 'Job Vacancy not found'],
                404
            );
        }
        if ($jobVacancy->vacancyStatus->vacancy_status_id == 2 || $jobVacancy->vacancyStatus->vacancy_status_id == 3) {
            return response()->json([
                'message' => 'Job Vacancy is closed',
            ], 400);
        }

        $jobVacancy = $this->JobVacancyService->updateJobVacancy($id, request()->all());
        return response()->json([
            'data' => new JobVacancyResource($jobVacancy)
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $jobVacancy = $this->JobVacancyService->getJobVacancyById($id);
        if (!$jobVacancy) {
            return response()->json(
                ['message' => 'Job Vacancy not found'],
                404
            );
        }
        if ($jobVacancy->vacancyStatus->vacancy_status_id == 3) {
            return response()->json([
                'message' => 'Job Vacancy is Archived',
            ], 400);
        }
        if ($jobVacancy->vacancyStatus->vacancy_status_id == 2) {
            return response()->json([
                'message' => 'Job Vacancy is closed',
            ], 400);
        }
        $jobVacancy = $this->JobVacancyService->deleteJobVacancy($id);
        if ($jobVacancy->status == 400) {
            return response()->json([
                'message' => 'there is one or more accepted employment applications',
            ], 400);
        } else {
            return response()->json([
                'data' => new JobVacancyResource($jobVacancy)
            ], 200);
        }
    }
}
