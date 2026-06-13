<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        return view('student.courses.index');
    }

    public function showLesson(): View
    {
        return view('student.courses.lesson');
    }

    public function certificates(): View
    {
        return view('student.certificate.index');
    }

    public function showCertificates(): View
    {
        return view('student.certificate.show');
    }

    public function progress() : View {
        return view('student.courses.progress');
    }
}
