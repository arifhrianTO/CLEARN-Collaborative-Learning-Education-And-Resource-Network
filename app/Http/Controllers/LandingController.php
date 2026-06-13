<?php

namespace App\Http\Controllers;

use App\Models\Category;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function course()
    {
        return view('landing.course');
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
