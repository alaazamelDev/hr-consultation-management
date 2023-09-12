<?php

namespace App\Domain\Repositories;

use App\Domain\Models\CD\CaseNote;
use Illuminate\Database\Eloquent\Builder;

interface CaseNoteRepositoryInterface
{

    public function getCaseNoteById(int $id): CaseNote|Builder|null;

    public function createCaseNote(array $data): CaseNote|Builder|null;

    public function updateCaseNote(int $id, array $data): CaseNote|Builder|null;


}
