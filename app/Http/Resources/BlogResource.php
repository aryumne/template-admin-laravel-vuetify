<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'short_desc'     => $this->short_desc,
            'thumb_url'      => $this->thumb_url,
            'desc'           => $this->desc,
            'is_recomended'  => $this->is_recomended,
            'blog_type_id'   => $this->blog_type_id,
            'blog_type_name' => $this->blogType->bt_name,
            'menu_name'      => $this->blogType->menu_name,
            'created_at'     => Carbon::parse($this->created_at)->toDateTimeString(),
        ];
    }
}
