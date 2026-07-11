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

        // --- Pengecekan Kunci (Locking) ---
        $course = $enrollment->course;
        $course->load(['sessions.lessons', 'sessions.exercise', 'sessions.finalProjects']);
        $linearCurriculum = [];
        foreach ($course->sessions as $session) {
            foreach ($session->lessons as $lesson) {
                $linearCurriculum[] = ['type' => 'lesson', 'id' => $lesson->id];
            }
            if ($session->exercise) {
                $linearCurriculum[] = ['type' => 'exercise', 'id' => $session->exercise->id];
            }
            foreach ($session->finalProjects as $fp) {
                $linearCurriculum[] = ['type' => 'final_project', 'id' => $fp->id];
            }
        }

        $completedLessons = is_array($enrollment->completed_lessons) ? $enrollment->completed_lessons : [];
        $attemptedExercises = $enrollment->exerciseResults()->with('exerciseAttempt')->get()
            ->pluck('exerciseAttempt.exercise_id')->filter()->unique()->toArray();
        $submittedProjects = $course->sessions->flatMap->finalProjects->map(function ($fp) use ($enrollment) {
            $result = $enrollment->finalProjectResults()->where('final_project_id', $fp->id)->first();
            if ($result && $result->submission_file) {
                if ($result->final_project_score === null || $result->final_project_score >= 70) {
                    return $fp->id;
                }
            }
            return null;
        })->filter()->toArray();

        $lastUnlockedIndex = 0;
        for ($i = 0; $i < count($linearCurriculum); $i++) {
            $item = $linearCurriculum[$i];
            $isCompleted = false;

            if ($item['type'] === 'lesson') {
                $isCompleted = in_array($item['id'], $completedLessons);
            } elseif ($item['type'] === 'exercise') {
                $isCompleted = in_array($item['id'], $attemptedExercises);
            } elseif ($item['type'] === 'final_project') {
                $isCompleted = in_array($item['id'], $submittedProjects);
            }

            if ($isCompleted) {
                $lastUnlockedIndex = $i + 1;
            } else {
                break;
            }
        }

        $requestedIndex = -1;
        foreach ($linearCurriculum as $index => $item) {
             if ($item['type'] === 'exercise' && $item['id'] === $exerciseId) {
                 $requestedIndex = $index;
                 break;
             }
        }

        if ($requestedIndex > $lastUnlockedIndex) {
            return redirect()->route('student.course.lesson', $exercise->session->course->course_slug)
                ->with('error', 'Latihan ini masih terkunci. Silakan selesaikan materi sebelumnya.');
        }
        // --- End Pengecekan Kunci ---
        
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
            
            // Rekalkulasi Progres
            $enrollment->recalculateAndSaveProgress();

            return compact('score', 'correctCount', 'totalQuestions');
        });

        // 3. Response 
        if ($result['totalQuestions'] === 0) {
            return back()->with('error', 'Tidak ada soal untuk latihan ini.');
        }

        return view('student.exercise.result', array_merge($result, ['exercise' => Exercise::with('session.course')->findOrFail($exerciseId)]));
    }
}


