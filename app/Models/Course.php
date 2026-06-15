<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_category_id',
        'title',
        'description',
        'image',
        'amount',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order')->orderBy('id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('id')->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getIsFreeAttribute()
    {
        return $this->amount <= 0;
    }
}
