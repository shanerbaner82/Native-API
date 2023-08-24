<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'uuid' => $this->uuid,
            'completed' => (bool) $this->completed_at ?? false,
            'currently_working_on' => (bool) $this->currently_working_on
        ];
    }
}
