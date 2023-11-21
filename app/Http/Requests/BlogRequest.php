<?php

namespace App\Http\Requests;

class BlogRequest extends BaseRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'title'          => ['required', 'string'],
            'short_desc'     => ['required', 'string'],
            'thumb_url'      => ['required', 'string'],
            'desc'           => ['required', 'string'],
            'is_recomended'  => ['required', 'boolean'],
            'blog_type_id'   => ['required', 'exists:App\Models\BlogType,id'],
        ]);
    }

    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'blog_type_id' => 'Blog type',
            'thumb_url' => 'Thumbnail',
        ]);
    }
}
