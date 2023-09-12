<?php

namespace App\Application\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBriefResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    /**
     *
     *  $table->id();
     * $table->UnsignedBigInteger('education_level_id');
     * $table->string('first_name');
     * $table->string('last_name');
     * $table->string('email')->unique()->nullable();
     * $table->string('username')->unique();
     * $table->string('password');
     * $table->string('job');
     * $table->date('birth_date');
     * $table->string('phone')->nullable();
     * $table->string('phone_number');
     * $table->integer('martial_status');
     * $table->integer('num_of_children');
     * $table->string('national_number');
     * $table->text('profile_picture')->nullable();
     * $table->boolean('verified')->default(false);
     * $table->boolean('blocked')->default(false);
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'national_number' => $this->national_number,
            'verified' => $this->verified,
            'blocked' => $this->blocked,
            'isUsingApp' => $this["isUsingApp"] ?? false,
        ];
    }
}
