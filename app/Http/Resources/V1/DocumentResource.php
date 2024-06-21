<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'contents' => $this->contents,
            'category_id' => $this->category_id,
            'financial_year' => $this->financial_year,
            'date' => $this->created_at->format('d/m/Y'),
            'category' => CategoryResource::make($this->whenLoaded('category')),
        ];
    }
}
