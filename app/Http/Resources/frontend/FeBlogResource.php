<?php

namespace App\Http\Resources\frontend;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeBlogResource extends JsonResource
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
            'urlImage'       => $this->thumb_url,
            'desc'           => $this->desc,
            'is_recomended'  => $this->is_recomended,
            'menuName'       => $this->blogType->menu_name,
            'date'           => Carbon::parse($this->created_at)->toFormattedDateString(),
        ];
    }
}
