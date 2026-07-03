<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use App\Models\Option;
use App\Models\Question;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    public function storeEmpty(Session $session)
    {
        $session->load([
            'course.sessions',
            'lessons',
            'exercise',
        ]);

        if ($session->course->mentor_id !== Auth::id()) {
            abort(403);
        }

        if ($this->isLastSession($session)) {
            return redirect()
                ->route('mentor.courses.sessions.edit', $session->course_id)
                ->with('error', 'Session terakhir khusus untuk final project. Kuis tidak bisa ditambahkan.');
        }

        if ($session->lessons->count() < 1) {
            return redirect()
                ->route('mentor.courses.sessions.edit', $session->course_id)
                ->with('error', 'Tambahkan lesson terlebih dahulu sebelum membuat kuis.');
        }

        if ($session->exercise) {
            return redirect()
                ->route('mentor.sessions.exercises.edit', $session->exercise->id)
                ->with('error', 'Session ini sudah memiliki kuis. Kamu bisa mengedit kuis yang sudah ada.');
        }

        $meetingNumber = $session->course->sessions
            ->sortBy('id')
            ->values()
            ->search(fn($item) => $item->id === $session->id) + 1;

        $exercise = Exercise::create([
            'sessions_id' => $session->id,
            'exercise_title' => 'Kuis: M' . $meetingNumber,
        ]);

        return redirect()
            ->route('mentor.sessions.exercises.edit', $exercise->id)
            ->with('success', 'Kuis berhasil dibuat. Silakan isi soal kuis.');
    }

    public function edit(Exercise $exercise)
    {
        $exercise->load([
            'session.course.sessions',
            'session.lessons',
            'questions.options',
        ]);

        if ($exercise->session->course->mentor_id !== Auth::id()) {
            abort(403);
        }

        return view('mentor.exercises.edit', compact('exercise'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $exercise->load([
            'session.course',
            'questions.options',
        ]);

        if ($exercise->session->course->mentor_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'exercise_title' => ['required', 'string', 'max:255'],

            'questions' => ['required', 'array', 'min:1'],

            'questions.*.id' => ['nullable', 'exists:questions,id'],
            'questions.*.question_text' => ['required', 'string'],

            'questions.*.options' => ['required', 'array', 'size:4'],
            'questions.*.options.*.id' => ['nullable', 'exists:options,id'],
            'questions.*.options.*.option_text' => ['required', 'string'],

            'questions.*.correct_option' => ['required', 'integer', 'between:0,3'],
        ]);

        DB::transaction(function () use ($validated, $exercise) {
            $exercise->update([
                'exercise_title' => $validated['exercise_title'],
            ]);

            $keptQuestionIds = [];

            foreach ($validated['questions'] as $questionData) {
                if (!empty($questionData['id'])) {
                    $question = Question::where('exercise_id', $exercise->id)
                        ->where('id', $questionData['id'])
                        ->firstOrFail();

                    $question->update([
                        'question_text' => $questionData['question_text'],
                    ]);
                } else {
                    $question = Question::create([
                        'exercise_id' => $exercise->id,
                        'question_text' => $questionData['question_text'],
                    ]);
                }

                $keptQuestionIds[] = $question->id;

                $keptOptionIds = [];

                foreach ($questionData['options'] as $optionIndex => $optionData) {
                    if (!empty($optionData['id'])) {
                        $option = Option::where('question_id', $question->id)
                            ->where('id', $optionData['id'])
                            ->firstOrFail();

                        $option->update([
                            'option_text' => $optionData['option_text'],
                            'is_correct' => (int) $questionData['correct_option'] === (int) $optionIndex,
                        ]);
                    } else {
                        $option = Option::create([
                            'question_id' => $question->id,
                            'option_text' => $optionData['option_text'],
                            'is_correct' => (int) $questionData['correct_option'] === (int) $optionIndex,
                        ]);
                    }

                    $keptOptionIds[] = $option->id;
                }

                Option::where('question_id', $question->id)
                    ->whereNotIn('id', $keptOptionIds)
                    ->delete();
            }

            Question::where('exercise_id', $exercise->id)
                ->whereNotIn('id', $keptQuestionIds)
                ->delete();
        });

        return redirect()
            ->route('mentor.courses.sessions.edit', $exercise->session->course_id)
            ->with('success', 'Kuis berhasil disimpan.');
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->load('session.course');

        if ($exercise->session->course->mentor_id !== Auth::id()) {
            abort(403);
        }

        $courseId = $exercise->session->course_id;

        $exercise->delete();

        return redirect()
            ->route('mentor.courses.sessions.edit', $courseId)
            ->with('success', 'Kuis berhasil dihapus.');
    }

    private function isLastSession(Session $session): bool
    {
        $session->loadMissing('course.sessions');

        $lastSession = $session->course->sessions
            ->sortBy('id')
            ->last();

        return $lastSession && $lastSession->id === $session->id;
    }
}
