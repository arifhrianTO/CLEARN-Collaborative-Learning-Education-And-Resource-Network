<?php

namespace App\Services\Student;

use App\Models\Enrollment;
use App\Models\ExerciseAttempt;
use App\Models\ExerciseResult;
use App\Models\Question;
use App\Models\StudentAnswer;
use Illuminate\Support\Facades\DB;

class ExerciseSubmissionService
{
    public function submit(int $exerciseId, array $answers, int $userId): array
    {
        return DB::transaction(function () use ($exerciseId, $answers, $userId) {
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
    }
}
