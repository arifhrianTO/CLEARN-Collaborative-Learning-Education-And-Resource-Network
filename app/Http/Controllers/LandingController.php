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

    public function tutorial()
    {
        $mentorCount = User::where('role', 'mentor')->where('status', 'active')->count();
        $studentCount = User::where('role', 'student')->where('status', 'active')->count();
        $courseCount = Course::where('status_publish', 'published')->count();

        return view('landing.tutorial', compact('mentorCount', 'studentCount', 'courseCount'));
    }

    public function course(\Illuminate\Http\Request $request)
    {
        $search = $request->query('search');

        $coursesQuery = Course::where('status_publish', 'published')
            ->with('enrollments')
            ->latest();

        if ($search) {
            $coursesQuery->where('course_title', 'like', "%{$search}%")
                ->orWhereHas('mentor', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $courses = $coursesQuery->get();

        return view('landing.course', compact('courses', 'search'));
    }

    public function category(\Illuminate\Http\Request $request)
    {
        $search = $request->query('search');

        $categoriesQuery = Category::withCount('courses');

        if ($search) {
            $categoriesQuery->where('category_name', 'like', "%{$search}%");
        }

        $categories = $categoriesQuery->get();

        return view('landing.category', compact('categories', 'search'));
    }

    public function mentor(\Illuminate\Http\Request $request)
    {
        $search = $request->query('search');

        $mentorsQuery = User::where('role', 'mentor')
            ->whereIn('status', ['active', 'verified', 'pending'])
            ->with(['profileAccount'])
            ->withCount(['courses' => function ($query) {
                $query->where('status_publish', 'published');
            }])
            ->with(['courses.enrollments']);

        if ($search) {
            $mentorsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('occupation', 'like', "%{$search}%");
            });
        }

        $mentors = $mentorsQuery->get()
            ->map(function ($mentor) {
                $mentor->student_count = $mentor->courses->sum(function ($course) {
                    return $course->enrollments->count();
                });
                return $mentor;
            });

        $totalMentors = $mentors->count();

        return view('landing.mentor', compact('mentors', 'totalMentors', 'search'));
    }
}
