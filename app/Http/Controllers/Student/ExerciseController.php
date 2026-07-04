<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Exercise;
use App\Models\ExerciseAttempt;
use App\Models\ExerciseResult;
use App\Models\Question;
use App\Models\StudentAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function show(int $exerciseId): View
    {
        $exercise = Exercise::with('questions.options')->findOrFail($exerciseId);

        return view('student.exercise', compact('exercise'));
    }

    public function submit(Request $request, int $exerciseId): RedirectResponse|View
    {
        // 1. Validasi Request (Sebelumnya di SubmitExerciseRequest)
        $validated = $request->validate([
            'answer'   => ['required', 'array'],
            'answer.*' => ['nullable', 'integer', 'exists:options,id'],
        ]);

        $answers = $validated['answer'];
        $userId = auth()->id();

        // 2. Logika Bisnis (Sebelumnya di ExerciseSubmissionService)
        $result = DB::transaction(function () use ($exerciseId, $answers, $userId) {
            $questions = Question::with('options')
                ->where('exercise_id', $exerciseId)
                ->get();

            $totalQuestions = $questions->count();

            if ($totalQuestions === 0) {
                return [
                    'score'          => 0,
                    'correctCount'   => 0,
                    'totalQuestions' => 0,
                ];
            }

            $enrollment = Enrollment::where('student_id', $userId)
                ->whereHas('course.sessions.exercises', function ($query) use ($exerciseId) {
                    $query->where('exercises.id', $exerciseId);
                })
                ->firstOrFail();

            $attemptNumber = ExerciseAttempt::where('enrollment_id', $enrollment->id)
                ->where('exercise_id', $exerciseId)
                ->count() + 1;

            $attempt = ExerciseAttempt::create([
                'enrollment_id'   => $enrollment->id,
                'exercise_id'     => $exerciseId,
                'attempt_number'  => $attemptNumber,
                'start_time'      => now(),
                'end_time'        => now(),
                'attempt_status'  => 'completed',
            ]);

            $correctCount = 0;

            foreach ($questions as $question) {
                $selectedOptionId = $answers[$question->id] ?? null;

                $isCorrect = $question->options
                    ->where('id', $selectedOptionId)
                    ->where('is_correct', true)
                    ->isNotEmpty();

                if ($isCorrect) {
                    $correctCount++;
                }

                StudentAnswer::create([
                    'exercise_attempt_id' => $attempt->id,
                    'question_id'         => $question->id,
                    'option_id'           => $selectedOptionId,
                ]);
            }

            $score = round(($correctCount / $totalQuestions) * 100);

            ExerciseResult::create([
                'exercise_attempt_id'   => $attempt->id,
                'enrollment_id'         => $enrollment->id,
                'exercise_result_score' => $score,
            ]);

            return compact('score', 'correctCount', 'totalQuestions');
        });

        // 3. Response 
        if ($result['totalQuestions'] === 0) {
            return back()->with('error', 'Tidak ada soal untuk latihan ini.');
        }

        return view('student.exercise-result', $result);
    }
}
