<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class productResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'primary_image' => url(env('PRODUCT_IMAGE_UPLOAD_PATH').$this->primary_image),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'delivery_amount' => $this->delivery_amount,
            'images' => ProductImageResource::collection($this->whenLoaded('productImage'))
        ];
    }
}
