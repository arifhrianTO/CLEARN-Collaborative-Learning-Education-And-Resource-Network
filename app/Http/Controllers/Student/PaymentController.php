<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function history(): View
    {
        return view('student.history.index');
    }

    public function checkout(int|string $course_id): View
    {
        return view('student.checkout', compact('course_id'));
    }
}
