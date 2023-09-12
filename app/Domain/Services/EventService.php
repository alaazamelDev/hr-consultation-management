<?php

namespace App\Domain\Services;

use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Models\CD\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EventService
{
    private EventRepositoryInterface $EventRepository;

    public function __construct(EventRepositoryInterface $EventRepository)
    {
        $this->EventRepository = $EventRepository;
    }

    public function getEventList(): LengthAwarePaginator
    {
        return $this->EventRepository->getEventList();
    }

    public function getEventById(int $id): Event|Builder|null
    {
        return $this->EventRepository->getEventById($id);
    }

    public function createEvent(array $data): Event|Builder|null
    {
        return $this->EventRepository->createEvent($data);
    }

    public function updateEvent(int $id, array $data): Event|Builder|null
    {
        return $this->EventRepository->updateEvent($id, $data);
    }

    public function deleteEvent($id): Event|Builder|null
    {
        return $this->EventRepository->deleteEvent($id);
    }
}
