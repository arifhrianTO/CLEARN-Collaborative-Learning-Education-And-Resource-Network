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
    public function quiz(int $exerciseId): View|RedirectResponse
    {
        $exercise = Exercise::with(['questions.options', 'session.course'])->findOrFail($exerciseId);

        $userId = auth()->id();

        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course.sessions.exercises', function ($query) use ($exerciseId) {
                $query->where('exercises.id', $exerciseId);
            })
            ->first();

        if (!$enrollment) {
             return redirect()->route('student.course.index')->with('error', 'Anda belum terdaftar di kursus ini.');
        }

        $hasAttempted = ExerciseAttempt::where('enrollment_id', $enrollment->id)
            ->where('exercise_id', $exerciseId)
            ->exists();

        if ($hasAttempted) {
             return redirect()->route('student.course.lesson', $exercise->session->course->course_slug)
                ->with('error', 'Anda sudah mengerjakan kuis ini sebelumnya. Kuis hanya dapat dikerjakan satu kali.');
        }

        return view('student.quiz.index', compact('exercise'));
    }

    public function show(int $exerciseId): View|RedirectResponse
    {
        $exercise = Exercise::with(['questions.options', 'session.course'])->findOrFail($exerciseId);

        // Periksa apakah student sudah pernah mengerjakan
        $userId = auth()->id();
        
        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course.sessions.exercises', function ($query) use ($exerciseId) {
                $query->where('exercises.id', $exerciseId);
            })
            ->first();
            
        if (!$enrollment) {
             return redirect()->route('student.course.index')->with('error', 'Anda belum terdaftar di kursus ini.');
        }
        
        $hasAttempted = ExerciseAttempt::where('enrollment_id', $enrollment->id)
            ->where('exercise_id', $exerciseId)
            ->exists();
            
        if ($hasAttempted) {
             return redirect()->route('student.course.lesson', $exercise->session->course->course_slug)
                ->with('error', 'Anda sudah mengerjakan latihan ini sebelumnya. Latihan hanya dapat dikerjakan satu kali.');
        }

        return view('student.exercise.confirm', compact('exercise'));
    }

    public function start(int $exerciseId): View|RedirectResponse
    {
        $exercise = Exercise::with(['questions.options', 'session.course'])->findOrFail($exerciseId);
        
        $userId = auth()->id();
        
        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course.sessions.exercises', function ($query) use ($exerciseId) {
                $query->where('exercises.id', $exerciseId);
            })
            ->first();
            
        if (!$enrollment) {
             return redirect()->route('student.course.index')->with('error', 'Anda belum terdaftar di kursus ini.');
        }
        
        $hasAttempted = ExerciseAttempt::where('enrollment_id', $enrollment->id)
            ->where('exercise_id', $exerciseId)
            ->exists();
            
        if ($hasAttempted) {
             return redirect()->route('student.course.lesson', $exercise->session->course->course_slug)
                ->with('error', 'Anda sudah mengerjakan latihan ini sebelumnya. Latihan hanya dapat dikerjakan satu kali.');
        }

        return view('student.exercise.show', compact('exercise'));
    }

    public function submit(Request $request, int $exerciseId): RedirectResponse|View
    {
        // 1. Validasi Request
        $validated = $request->validate([
            'answer'   => ['required', 'array'],
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
                'attempt_status'  => 'submitted',
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

                if ($selectedOptionId) {
                    StudentAnswer::create([
                        'exercise_attempt_id' => $attempt->id,
                        'question_id'         => $question->id,
                        'option_id'           => $selectedOptionId,
                    ]);
                }
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

        return view('student.exercise.result', array_merge($result, ['exercise' => Exercise::with('session.course')->findOrFail($exerciseId)]));
    }
}


