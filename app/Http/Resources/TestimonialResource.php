<?php

namespace App\Http\Resources;

use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Testimonial */
class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role_or_title' => $this->role_or_title,
            'company' => $this->company,
            'avatar' => MediaUrl::absolute($this->avatar),
            'quote' => $this->quote,
            'rating' => $this->rating,
        ];
    }
}
