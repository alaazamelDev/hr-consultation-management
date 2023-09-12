<?php
namespace App\Application\Http\Controllers;
use App\Application\Http\Resources\JobTitleResource;
use App\Domain\Services\JobTitleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function Illuminate\Events\queueable;

class JobTitleController extends Controller
{
    private $JobTitleService;

    public function __construct(JobTitleService $JobTitleService)
    {
        $this->JobTitleService = $JobTitleService;
    }

    public function index(): JsonResponse
    {
        $jobTitles = $this->JobTitleService->getJobTitleList();
        return response()->json([
            'data'=>JobTitleResource::collection($jobTitles)
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        $jobTitle = $this->JobTitleService->getJobTitleById($id);
        if(!$jobTitle){
            return response()->json(['message'=>'Job Title not found']
                , 404);
        }
        return response()->json([
            'data'=> new JobTitleResource($jobTitle)
        ], 200);
    }

    public function store(): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['required','max:50','string',
                Rule::unique('job_titles', 'name')->whereNull('deleted_at')],
            'permissions_ids'=>['required','array','min:1'],
            'permissions_ids.*' => ['integer','exists:permissions,perm_id'],
            'description' =>['required','max:255','string','nullable']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=>$errors
            ], 400);
        }
        $jobTitle = $this->JobTitleService->createJobTitle(request()->all());
        return response()->json([
            'data'=> new JobTitleResource($jobTitle)
        ], 200);
    }

    public function update(int $id): JsonResponse
    {
        $validator = Validator::make(request()->all(), [
            'name' => ['max:50','string',
                Rule::unique('job_titles', 'name')->whereNull('deleted_at')->ignore($id, 'job_title_id')],
            'permissions_ids'=>['sometimes','array','min:1'],
            'permissions_ids.*' => ['integer','exists:permissions,perm_id'],
            'description' =>['max:255','string','nullable']
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'errors'=> $errors
            ], 400);
        }

        $jobTitle = $this->JobTitleService->getJobTitleById($id);
        if(!$jobTitle){
            return response()->json(['message' => 'Job Title not found']
                , 404);
        }
        $jobTitle = $this->JobTitleService->updateJobTitle($id, request()->all());

        return response()->json([
            'data'=> new JobTitleResource($jobTitle)
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $jobTitle = $this->JobTitleService->deleteJobTitle($id);

        if(!$jobTitle){
            return response()->json(['message' => 'Job Title not found']
                , 404);
        }

        if($jobTitle['employees_count']>0){
            return response()->json([
                'message' => 'There is one or more employees have this Job title',
            ], 400);
        }
        return response()->json([
            'data'=> new JobTitleResource($jobTitle)
        ], 200);
    }
}
