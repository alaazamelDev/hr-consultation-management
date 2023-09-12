<?php

namespace App\Domain\Services;

use App\Domain\Models\CD\CaseNote;
use App\Domain\Repositories\CaseNoteRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class CaseNoteService
{
    private CaseNoteRepositoryInterface $CaseNoteRepository;

    public function __construct(CaseNoteRepositoryInterface $CaseNoteRepository)
    {
        $this->CaseNoteRepository = $CaseNoteRepository;
    }

    public function getCaseNoteById(int $id): CaseNote|Builder|null
    {
        return $this->CaseNoteRepository->getCaseNoteById($id);
    }

    public function createCaseNote(array $data): CaseNote|Builder|null
    {
        return $this->CaseNoteRepository->createCaseNote($data);
    }

    public function updateCaseNote(int $id, array $data): CaseNote|Builder|null
    {
        return $this->CaseNoteRepository->updateCaseNote($id, $data);
    }

}
