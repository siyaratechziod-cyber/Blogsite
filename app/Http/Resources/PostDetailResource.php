<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

/** @mixin \App\Models\Post */
class PostDetailResource extends PostResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'content' => $this->content,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'comments' => CommentResource::collection($this->whenLoaded('approvedComments')),
        ]);
    }
}
