<?php

namespace App\Application\Http\Controllers;

use App\Application\Http\Requests\AddCaseNoteRequest;
use App\Application\Http\Requests\UpdateCaseNoteRequest;
use App\Application\Http\Resources\CaseNoteResource;
use App\Domain\Models\CD\CaseNote;
use App\Domain\Services\CaseNoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CaseNoteController extends Controller
{
    private CaseNoteService $CaseNoteService;

    public function __construct(CaseNoteService $CaseNoteService)
    {
        $this->CaseNoteService = $CaseNoteService;
    }

    public function show(int $id): CaseNoteResource
    {
        $caseNote = $this->CaseNoteService->getCaseNoteById($id);
        return new CaseNoteResource($caseNote);
    }

    public function store(AddCaseNoteRequest $request): CaseNoteResource
    {
        $validated = $request->validated();
        $case_note = $this->CaseNoteService->createCaseNote($validated);
        return new CaseNoteResource($case_note);
    }

    public function update(UpdateCaseNoteRequest $request, $id): CaseNoteResource
    {
        $caseNote = $this->CaseNoteService->updateCaseNote($id, $request->validated());
        return new CaseNoteResource($caseNote);

    }

}
