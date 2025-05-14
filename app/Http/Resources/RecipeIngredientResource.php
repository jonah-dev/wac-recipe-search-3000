<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeIngredientResource extends JsonResource
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
            'ingredient' => $this->whenLoaded('ingredient') ? $this->ingredient->name : null,
            'unit' => $this->whenLoaded('unit') ? $this->unit->name ?? null : null,
            'amount' => $this->amount,
            'descriptor' => $this->whenLoaded('descriptor') ? $this->descriptor->name ?? null : null,
        ];
    }
}
