<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected $guarded = ['id'];
    protected $table = 'blogs';

    protected static function booted(): void
    {
        static::creating(function (Blog $model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function (Blog $model) {
            $model->slug = Str::slug($model->title);
        });
    }

    public function blogType(): BelongsTo
    {
        return $this->belongsTo(BlogType::class, 'blog_type_id', 'id');
    }
}
