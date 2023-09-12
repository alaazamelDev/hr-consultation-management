<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed computer_skill_id
 * @property mixed name
 * @property mixed pivot
 */
class ComputerSkillResource extends JsonResource
{

    public function toArray(Request $request)
    {
        return [
            'computer_skill_id' => $this->computer_skill_id,
            'skill_name' => $this->name,
            'level' => $this->pivot->level,
        ];
    }
}
