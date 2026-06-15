<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Lesson;
use App\Models\Settings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    public function showCourses(Request $request)
    {
        $settings = Settings::find(1);
        $courses = Course::with('category', 'lessons', 'users')
            ->when($request->searchValue, fn($q, $v) => $q->where('title', 'like', "%{$v}%"))
            ->latest()
            ->get();
        $categories = CourseCategory::all();

        return view('admin.memebership.courses', [
            'courses' => $courses,
            'categories' => $categories,
            'title' => 'Courses',
            'settings' => $settings,
        ]);
    }

    public function addCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|exists:course_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:1000',
            'image_url' => 'nullable|string|max:500',
        ]);

        if (!$request->image_url && !$request->hasFile('image')) {
            return redirect()->back()->with('message', 'Please choose a course image');
        }

        $path = $request->hasFile('image')
            ? $request->file('image')->store('uploads', 'public')
            : $request->image_url;

        Course::create([
            'title' => $request->title,
            'description' => $request->desc,
            'amount' => $request->amount ?? 0,
            'course_category_id' => $request->category,
            'image' => $path,
            'is_published' => false,
        ]);

        return back()->with('success', 'Course created successfully');
    }

    public function updateCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'amount' => 'nullable|numeric|min:0',
            'category' => 'nullable|exists:course_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:1000',
            'image_url' => 'nullable|string|max:500',
            'is_published' => 'nullable|boolean',
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
        } elseif ($request->image_url) {
            $path = $request->image_url;
        } else {
            $path = $course->image;
        }

        $course->update([
            'title' => $request->title,
            'description' => $request->desc,
            'amount' => $request->amount ?? 0,
            'course_category_id' => $request->category,
            'image' => $path,
            'is_published' => $request->boolean('is_published', $course->is_published),
        ]);

        return back()->with('success', 'Course updated successfully');
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);

        if ($course->image && !str_starts_with($course->image, 'http') && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return back()->with('success', 'Course deleted successfully');
    }

    public function togglePublish($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['is_published' => !$course->is_published]);

        $status = $course->is_published ? 'published' : 'unpublished';
        return back()->with('success', "Course {$status} successfully");
    }

    public function showLessons($id)
    {
        $settings = Settings::find(1);
        $course = Course::with(['lessons' => fn($q) => $q->ordered(), 'users'])->findOrFail($id);

        return view('admin.memebership.lessons', [
            'lessons' => $course->lessons,
            'course' => $course,
            'enrollments' => $course->users,
            'title' => 'Lessons',
            'settings' => $settings,
        ]);
    }

    public function addLesson(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'videolink' => 'required|string|max:500',
            'length' => 'nullable|string|max:50',
            'preview' => 'nullable|in:true,false',
            'course_id' => 'nullable|exists:courses,id',
            'category' => 'nullable|exists:course_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:1000',
            'image_url' => 'nullable|string|max:500',
        ]);

        if (!$request->image_url && !$request->hasFile('image')) {
            return redirect()->back()->with('message', 'Please choose a lesson thumbnail');
        }

        $path = $request->hasFile('image')
            ? $request->file('image')->store('uploads', 'public')
            : $request->image_url;

        $maxOrder = 0;
        if ($request->course_id) {
            $maxOrder = Lesson::where('course_id', $request->course_id)->max('sort_order') ?? 0;
        }

        Lesson::create([
            'title' => $request->title,
            'description' => $request->desc,
            'video_link' => $request->videolink,
            'length' => $request->length,
            'is_preview' => $request->preview === 'true',
            'course_id' => $request->course_id,
            'course_category_id' => $request->category,
            'thumbnail' => $path,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Lesson added successfully');
    }

    public function updateLesson(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'videolink' => 'required|string|max:500',
            'length' => 'nullable|string|max:50',
            'preview' => 'nullable|in:true,false',
            'course_id' => 'nullable|exists:courses,id',
            'category' => 'nullable|exists:course_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:1000',
            'image_url' => 'nullable|string|max:500',
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
        } elseif ($request->image_url) {
            $path = $request->image_url;
        } else {
            $path = $lesson->thumbnail;
        }

        $lesson->update([
            'title' => $request->title,
            'description' => $request->desc,
            'video_link' => $request->videolink,
            'length' => $request->length,
            'is_preview' => $request->preview === 'true',
            'course_id' => $request->course_id,
            'course_category_id' => $request->category,
            'thumbnail' => $path,
        ]);

        return back()->with('success', 'Lesson updated successfully');
    }

    public function deleteLesson($id)
    {
        $lesson = Lesson::findOrFail($id);

        if ($lesson->thumbnail && !str_starts_with($lesson->thumbnail, 'http') && Storage::disk('public')->exists($lesson->thumbnail)) {
            Storage::disk('public')->delete($lesson->thumbnail);
        }

        $lesson->delete();

        return back()->with('success', 'Lesson deleted successfully');
    }

    public function reorderLesson(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'direction' => 'required|in:up,down',
        ]);

        $lesson = Lesson::findOrFail($request->lesson_id);

        if ($request->direction === 'up') {
            $swap = Lesson::where('course_id', $lesson->course_id)
                ->where('sort_order', '<', $lesson->sort_order)
                ->orderByDesc('sort_order')->first();
        } else {
            $swap = Lesson::where('course_id', $lesson->course_id)
                ->where('sort_order', '>', $lesson->sort_order)
                ->orderBy('sort_order')->first();
        }

        if ($swap) {
            $tempOrder = $lesson->sort_order;
            $lesson->update(['sort_order' => $swap->sort_order]);
            $swap->update(['sort_order' => $tempOrder]);
        }

        return back()->with('success', 'Lesson reordered successfully');
    }

    public function category()
    {
        $settings = Settings::find(1);
        $categories = CourseCategory::withCount(['courses', 'standaloneLessons'])->get();

        return view('admin.memebership.category', [
            'categories' => $categories,
            'title' => 'Course Category',
            'settings' => $settings,
        ]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255|unique:course_categories,name',
        ]);

        CourseCategory::create(['name' => $request->category]);

        return back()->with('success', 'Category created successfully');
    }

    public function deleteCategory($id)
    {
        $category = CourseCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category deleted successfully');
    }

    public function lessonWithoutCourse()
    {
        $settings = Settings::find(1);
        $lessons = Lesson::standalone()->with('courseCategory')->ordered()->get();
        $categories = CourseCategory::all();

        return view('admin.memebership.lessons-without', [
            'title' => 'Lessons without courses',
            'lessons' => $lessons,
            'categories' => $categories,
            'settings' => $settings,
        ]);
    }

    // Edit course enrollment date (backdate)
    public function editEnrollment(int $id)
    {
        $enrollment = DB::table('course_user')->where('id', $id)->first();
        abort_if(!$enrollment, 404);

        $course = Course::findOrFail($enrollment->course_id);
        $title = 'Edit Enrollment #' . $id;
        return view('admin.memebership.enrollment-edit', compact('enrollment', 'course', 'title'));
    }

    public function updateEnrollment(Request $request, int $id)
    {
        $request->validate([
            'created_at' => 'required|date',
        ]);

        $enrollment = DB::table('course_user')->where('id', $id)->first();
        abort_if(!$enrollment, 404);

        DB::table('course_user')->where('id', $id)->update([
            'created_at' => Carbon::parse($request->created_at),
        ]);

        return redirect()->route('courses')->with('success', 'Enrollment date updated successfully!');
    }
}
