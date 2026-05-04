<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'description' => $this->description ?? null,
            'visibility' => $this->visibility->value,
            'background' => $this->background,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'owner' => new UserResource($this->whenLoaded('owner')),
        ];
    }
}
