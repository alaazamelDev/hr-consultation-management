<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $log_id
 * @property mixed $action
 * @property mixed $user
 * @property mixed $affectedUser
 * @property mixed $description
 * @property mixed $date
 */
class LogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'log_id' => $this->log_id,

            // action that was done
            'action' => new ActionResource($this->action),

            // user who did the action
            'user' => new AffectedUserResource($this->user),

            // affected users
            'affected_users' => AffectedUserResource::collection($this->affectedUser),

            'description' => $this->description,

            'log_date' => $this->date,
        ];
    }
}
