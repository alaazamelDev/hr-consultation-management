<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Repositories\EventRepositoryInterface;
use App\Domain\Models\CD\Event;
use App\Exceptions\EntryNotFoundException;
use App\Utils\StorageUtilities;
use Bepsvpt\Blurhash\Facades\BlurHash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentEventRepository implements EventRepositoryInterface
{
    public function getEventList(): LengthAwarePaginator
    {
        $events = Event::query()->latest('start_date');

        if (request()->has('name')) {
            $name = request()->query('name');

            $name = trim($name);

            $name = strtolower($name);

            $events->whereRaw('LOWER(title) LIKE ?', ["%$name%"]);
        }

        return $events->paginate(10);
    }

    public function getEventById(int $id): Event|Builder|null
    {
        try {
            $event = Event::query()
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (\Exception $e) {
            throw new EntryNotFoundException("Event with id $id not found");
        }

        return $event;
    }

    public function createEvent(array $data): Event|Builder|null
    {
        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageData = StorageUtilities::storeEventPhoto($image);

            $blurhashCode = BlurHash::encode($image);
        }

        return Event::query()->create([
            'title' => $data['title'],
            'address' => $data['address'],
            'side_address' => $data['side_address'] ?? null,
            'description' => $data['description'] ?? null,
            'link' => $data['link'] ?? null,
            'image' => $imageData ?? null,
            'blurhash' => $blurhashCode ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
        ]);
    }

    public function updateEvent(int $id, array $data): Event|Builder|null
    {
        try {
            $event = Event::query()
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (\Exception $e) {
            throw new EntryNotFoundException("Event with id $id not found");
        }

        if (request()->hasFile('image')) {
            $image = request()->file('image');
            $imageData = StorageUtilities::storeEventPhoto($image);

//            $blurhash = new Blurhash();
//            // Generate BlurHash code for the image
//            $blurhashCode = $blurhash->encode($image);
            $blurhashCode = BlurHash::encode($image);
        }

        $event->update([
            'title' => $data['title'] ?? $event['title'],
            'address' => $data['address'] ?? $event['address'],
            'side_address' => $data['side_address'] ?? $event['side_address'],
            'description' => $data['description'] ?? $event['description'],
            'link' => $data['link'] ?? $event['link'],
            'image' => $imageData ?? $event['image'],
            'blurhash' => $blurhashCode ?? $event['blurhash'],
            'start_date' => $data['start_date'] ?? $event['start_date'],
            'end_date' => $data['end_date'] ?? $event['end_date'],
        ]);

        return $event;
    }

    public function deleteEvent($id): Event|Builder|null
    {
        try {
            $event = Event::query()
                ->where('id', '=', $id)
                ->firstOrFail();
        } catch (\Exception $e) {
            throw new EntryNotFoundException("Event with id $id not found");
        }
        $event->delete();

        return $event;
    }
}
