<?php

namespace App\Domain\Repositories;

use App\Domain\Models\CD\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
{
    public function getEventList(): LengthAwarePaginator;

    public function getEventById(int $id): Event|Builder|null;

    public function createEvent(array $data): Event|Builder|null;

    public function updateEvent(int $id, array $data): Event|Builder|null;

    public function deleteEvent($id): Event|Builder|null;
}
