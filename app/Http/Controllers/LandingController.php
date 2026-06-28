<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;

class LandingController extends Controller
{
    public function index()
    {
        $courses = Course::where('status_publish', 'published')
            ->with('enrollments')
            ->latest()
            ->take(6)
            ->get();

        return view('landing.index', compact('courses'));
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
