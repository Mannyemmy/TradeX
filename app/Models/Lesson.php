<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'course_category_id',
        'title',
        'description',
        'video_link',
        'thumbnail',
        'length',
        'is_preview',
        'sort_order',
    ];

    protected $casts = [
        'is_preview' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function scopeStandalone($query)
    {
        return $query->whereNull('course_id')->whereNotNull('course_category_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
