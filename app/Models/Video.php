<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'duration_seconds',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    // 👇 Add these accessors
    public function getVideoUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function getThumbnailUrlAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
