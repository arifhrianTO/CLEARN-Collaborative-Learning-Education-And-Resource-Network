<?php

namespace App\Services\Mentor;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\Option;
use App\Models\Question;
use App\Models\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseService
{
    public function create(array $data, $request): Course
    {
        return DB::transaction(function () use ($data, $request) {
            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');

            $course = Course::create([
                'mentor_id'          => auth()->id(),
                'category_id'        => $data['category_id'],
                'course_title'       => $data['title'],
                'course_slug'        => Str::slug($data['title']) . '-' . time(),
                'course_description' => $data['description'],
                'course_price'       => $data['price'],
                'course_thumbnail'   => $thumbnailPath,
                'status_publish'     => 'draft',
                'status_review'      => 'pending',
            ]);

            foreach ($data['modules'] as $moduleIndex => $moduleData) {
                $session = Session::create([
                    'course_id'             => $course->id,
                    'sessions_title'        => $moduleData['title'],
                    'sessions_description'  => $moduleData['description'] ?? null,
                ]);

                $this->storeLessons($session, $moduleIndex, $moduleData, $request);
                $this->storeExercise($session, $moduleData);
            }

            return $course;
        });
    }

    private function storeLessons(Session $session, int|string $moduleIndex, array $moduleData, $request): void
    {
        $types = $moduleData['content_types'] ?? [];
        $titles = $moduleData['content_titles'] ?? [];

        $videoIndex = 0;
        $pdfIndex = 0;

        foreach ($types as $index => $type) {
            $title = $titles[$index] ?? 'Materi';
            $path = null;

            if ($type === 'video') {
                $file = $request->file("modules.$moduleIndex.videos.$videoIndex");
                $videoIndex++;
                $path = $file?->store('courses/videos', 'public');
            } elseif ($type === 'pdf') {
                $file = $request->file("modules.$moduleIndex.pdfs.$pdfIndex");
                $pdfIndex++;
                $path = $file?->store('courses/pdfs', 'public');
            } else {
                continue;
            }

            $lesson = Lesson::create([
                'sessions_id'         => $session->id,
                'lessons_title'       => $title,
                'lessons_description' => $moduleData['title'],
            ]);

            LessonMaterial::create([
                'lesson_id'  => $lesson->id,
                'type'       => $type,
                'file_path'  => $path,
                'url'        => null,
            ]);
        }
    }

    private function storeExercise(Session $session, array $moduleData): void
    {
        if (empty($moduleData['exercise_questions'])) {
            return;
        }

        $exercise = Exercise::create([
            'sessions_id'     => $session->id,
            'exercise_title'  => 'Kuis - ' . $moduleData['title'],
        ]);

        foreach ($moduleData['exercise_questions'] as $questionData) {
            $question = Question::create([
                'exercise_id'   => $exercise->id,
                'question_text' => $questionData['text'],
            ]);

            foreach (['a', 'b', 'c', 'd'] as $key) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $questionData[$key],
                    'is_correct'  => $questionData['correct'] === $key,
                ]);
            }
        }
    }
}
