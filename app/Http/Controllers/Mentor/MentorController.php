<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    public function myCourses()
    {
        return view('mentor.courses.course');
    }

    public function revenue()
    {
        return view('mentor.revenue');
    }

    public function requestPayout(Request $request)
    {
        // logic payout
        return back()->with('success', 'Payout requested');
    }
}