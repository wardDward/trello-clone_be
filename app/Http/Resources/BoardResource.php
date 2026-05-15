<?php

namespace App\Http\Resources;

use App\Http\Enums\Board\BoardBackground;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'background' => $this->background_type === BoardBackground::FILE ? Storage::url($this->background) : $this->background,
            'backgroundType' => $this->background_type?->value,
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
            'owner' => new UserResource($this->whenLoaded('owner')),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'lists' => BoardListResource::collection($this->whenLoaded('lists'))
        ];
    }
}
