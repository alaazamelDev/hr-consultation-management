<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\Permission;

/**
 * @property mixed $perm_id
 * @property mixed $name
 * @property mixed $description
 * @property mixed $pivot
 */
class PermissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
          "perm_id" => $this->perm_id,
          "name" => $this->name,
          "description" => $this->description,
          "status" => $this->pivot->status ?? null,
        ];
    }
}
