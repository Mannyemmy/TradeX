<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Tp_Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function courses()
    {
        return view('user.membership.courses', [
            'title' => 'Courses',
        ]);
    }

    public function courseDetails($slug, $id)
    {
        $course = Course::published()->with(['lessons' => fn($q) => $q->ordered(), 'category'])->findOrFail($id);

        return view('user.membership.courseDetails', [
            'title' => 'Course Details',
            'course' => $course,
            'lessons' => $course->lessons,
        ]);
    }

    public function myCoursesDetails($id)
    {
        $course = Auth::user()->courses()->with(['lessons' => fn($q) => $q->ordered(), 'category'])->findOrFail($id);

        return view('user.membership.mycourse-details', [
            'title' => 'Course Details',
            'course' => $course,
            'lessons' => $course->lessons,
        ]);
    }

    public function myCourses()
    {
        $courses = Auth::user()->courses()->with(['lessons', 'category'])->get();

        return view('user.membership.my-course', [
            'title' => 'My Courses',
            'courses' => $courses,
        ]);
    }

    public function learning($lessonId, $courseId = null)
    {
        $lesson = Lesson::findOrFail($lessonId);
        $course = $courseId ? Course::find($courseId) : null;

        $next = null;
        $previous = null;

        if ($course) {
            $next = Lesson::where('course_id', $course->id)
                ->where(function ($q) use ($lesson) {
                    $q->where('sort_order', '>', $lesson->sort_order)
                      ->orWhere(function ($q2) use ($lesson) {
                          $q2->where('sort_order', $lesson->sort_order)
                             ->where('id', '>', $lesson->id);
                      });
                })
                ->orderBy('sort_order')->orderBy('id')->value('id');

            $previous = Lesson::where('course_id', $course->id)
                ->where(function ($q) use ($lesson) {
                    $q->where('sort_order', '<', $lesson->sort_order)
                      ->orWhere(function ($q2) use ($lesson) {
                          $q2->where('sort_order', $lesson->sort_order)
                             ->where('id', '<', $lesson->id);
                      });
                })
                ->orderByDesc('sort_order')->orderByDesc('id')->value('id');
        }

        return view('user.membership.watchlesson', [
            'course' => $course,
            'lesson' => $lesson,
            'title' => 'Watch Lesson',
            'next' => $next,
            'previous' => $previous,
        ]);
    }

    public function buyCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = User::findOrFail(Auth::id());
        $course = Course::published()->findOrFail($request->course_id);
        $amount = $course->amount ?? 0;

        if ($user->courses()->where('course_id', $course->id)->exists()) {
            return redirect()->back()->with('message', 'You have already purchased this course, you can view it on my course page');
        }

        if ($user->account_bal < $amount) {
            return redirect()->back()->with('message', 'You have insufficient funds in your account balance to make this purchase, please make a deposit');
        }

        $user->account_bal -= $amount;
        $user->save();

        $user->courses()->attach($course->id);

        Tp_Transaction::create([
            'user' => $user->id,
            'plan' => 'Purchase Course',
            'amount' => $amount,
            'type' => 'Education',
        ]);

        \App\Services\NotificationService::notifyUser($user, 'course', 'Course Purchased', 'You have successfully purchased the course "' . $course->title . '".', url('dashboard/my-courses'));
        \App\Services\NotificationService::notifyAdmin('course', 'New Course Purchase', $user->name . ' purchased the course "' . $course->title . '" for $' . number_format($amount, 2) . '.', url('admin/dashboard/courses'));

        return redirect()->back()->with('success', 'Course purchased successfully!');
    }
}
