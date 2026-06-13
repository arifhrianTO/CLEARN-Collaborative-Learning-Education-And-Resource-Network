<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\StudentIndexRequest;
use App\Services\Mentor\StudentService;

class StudentController extends Controller
{
    public function index(StudentIndexRequest $request, StudentService $studentService)
    {
        $data = $studentService->getStudentData(
            auth()->id(),
            $request->filters()
        );

        return view('mentor.student.index', $data);
    }
}
