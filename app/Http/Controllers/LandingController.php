<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        $courses = Course::where('status_publish', 'published')
            ->with('enrollments')
            ->latest()
            ->take(6)
            ->get();

        $heroCategories = Category::pluck('category_name')->toArray();
        $studentCount = \App\Models\User::where('role', 'student')->count();
        $courseCount = Course::where('status_publish', 'published')->count();
        $mentorCount = \App\Models\User::where('role', 'mentor')->count();

        $mentors = User::where('role', 'mentor')
            ->withCount(['courses' => function ($query) {
                $query->where('status_publish', 'published');
            }])
            ->with(['courses.enrollments'])
            ->take(4)
            ->get()
            ->map(function ($mentor) {
                $mentor->student_count = $mentor->courses->sum(function ($course) {
                    return $course->enrollments->count();
                });
                return $mentor;
            });


        return view('landing.index', compact('courses', 'heroCategories', 'studentCount', 'courseCount', 'mentorCount', 'mentors'));
    }

    public function course()
    {
        $courses = Course::where('status_publish', 'published')
            ->with('enrollments')
            ->latest()
            ->get();

        return view('landing.course', compact('courses'));
    }

    public function category()
    {
        $categories = Category::all();
        return view('landing.category', compact('categories'));
    }

    public function mentor()
    {
        return view('landing.mentor');
    }
}
