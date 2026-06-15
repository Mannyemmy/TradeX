<?php

namespace App\Http\Livewire\User;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Settings;
use Livewire\Component;

class SystemCourses extends Component
{
    public $courses = [];
    public $categories = [];

    public function mount()
    {
        $this->courses = Course::published()
            ->with(['lessons', 'users', 'category'])
            ->latest()
            ->get();

        $this->categories = CourseCategory::with(['standaloneLessons' => fn($q) => $q->ordered()])
            ->get()
            ->filter(fn($cat) => $cat->standaloneLessons->isNotEmpty())
            ->values();
    }

    public function render()
    {
        $settings = Settings::find(1);

        return view('livewire.user.system-courses', [
            'settings' => $settings,
        ]);
    }
}