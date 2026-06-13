<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\SubmitExerciseRequest;
use App\Models\Exercise;
use App\Services\Student\ExerciseSubmissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function __construct(private readonly ExerciseSubmissionService $exerciseSubmissionService)
    {
    }

    public function show(int $exerciseId): View
    {
        $exercise = Exercise::with('questions.options')->findOrFail($exerciseId);

        return view('student.exercise', compact('exercise'));
    }

    public function submit(SubmitExerciseRequest $request, int $exerciseId): RedirectResponse|View
    {
        $result = $this->exerciseSubmissionService->submit(
            $exerciseId,
            $request->validated('answer'),
            auth()->id()
        );

        if ($result['totalQuestions'] === 0) {
            return back()->with('error', 'Tidak ada soal untuk latihan ini.');
        }

        return view('student.exercise-result', $result);
    }
}
