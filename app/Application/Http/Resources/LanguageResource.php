<?php


namespace App\Application\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed language_id
 * @property mixed name
 * @property mixed pivot
 * @property mixed speaking_level
 * @property mixed writing_level
 * @property mixed reading_level
 */
class LanguageResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'language_id' => $this->language_id,
            'language_name' => $this->name,
            'speaking' => $this->pivot->speaking_level,
            'writing' => $this->pivot->writing_level,
            'reading' => $this->pivot->reading_level,
        ];
    }
}
