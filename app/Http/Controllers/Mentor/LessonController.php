<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Session;
use App\Models\LessonMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function create(Session $session)
    {
        $session->load('course.sessions');

        if ($session->course->mentor_id !== auth()->id()) {
            abort(403);
        }

        if ($this->isLastSession($session)) {
            return redirect()
                ->route('mentor.courses.sessions.edit', $session->course_id)
                ->with('error', 'Session terakhir khusus untuk final project. Lesson tidak bisa ditambahkan.');
        }

        return view('mentor.lessons.create', compact('session'));
    }

    public function store(Request $request, Session $session)
    {
        $session->load('course.sessions');

        if ($session->course->mentor_id !== auth()->id()) {
            abort(403);
        }

        if ($this->isLastSession($session)) {
            return redirect()
                ->route('mentor.courses.sessions.edit', $session->course_id)
                ->with('error', 'Session terakhir khusus untuk final project. Lesson tidak bisa ditambahkan.');
        }

        $request->validate([
            'lessons_title' => 'required|string|max:255',
            'lessons_description' => 'nullable|string',
            'material_file' => 'nullable|file|mimes:mp4,mov,avi,mkv,webm,pdf|max:51200',
            'material_url' => 'nullable|url',
        ]);

        if (!$request->hasFile('material_file') && !$request->filled('material_url')) {
            return back()
                ->withErrors(['material' => 'Upload file materi atau isi link materi wajib diisi salah satu.'])
                ->withInput();
        }

        $lesson = Lesson::create([
            'sessions_id' => $session->id,
            'lessons_title' => $request->lessons_title,
            'lessons_description' => $request->lessons_description,
        ]);

        $this->storeMaterials($request, $lesson);

        return redirect()
            ->route('mentor.courses.sessions.edit', $session->course_id)
            ->with('success', 'Lesson dan sumber materi berhasil ditambahkan.');
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load('session.course.sessions', 'materials');

        if ($lesson->session->course->mentor_id !== auth()->id()) {
            abort(403);
        }

        if ($this->isLastSession($lesson->session)) {
            return redirect()
                ->route('mentor.courses.sessions.edit', $lesson->session->course_id)
                ->with('error', 'Lesson di session terakhir tidak bisa diedit.');
        }

        return view('mentor.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $lesson->load('session.course.sessions', 'materials');

        if ($lesson->session->course->mentor_id !== auth()->id()) {
            abort(403);
        }

        if ($this->isLastSession($lesson->session)) {
            return redirect()
                ->route('mentor.courses.sessions.edit', $lesson->session->course_id)
                ->with('error', 'Lesson di session terakhir tidak bisa diedit.');
        }

        $request->validate([
            'lessons_title' => 'required|string|max:255',
            'lessons_description' => 'nullable|string',
            'material_file' => 'nullable|file|mimes:mp4,mov,avi,mkv,webm,pdf|max:51200',
            'material_url' => 'nullable|url',
        ]);

        $lesson->update([
            'lessons_title' => $request->lessons_title,
            'lessons_description' => $request->lessons_description,
        ]);

        if ($request->hasFile('material_file') || $request->filled('material_url')) {
            $this->storeMaterials($request, $lesson);
        }

        return redirect()
            ->route('mentor.courses.sessions.edit', $lesson->session->course_id)
            ->with('success', 'Lesson berhasil diperbarui.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->load('session.course', 'materials');

        if ($lesson->session->course->mentor_id !== auth()->id()) {
            abort(403);
        }

        $courseId = $lesson->session->course_id;

        foreach ($lesson->materials as $material) {
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
        }

        $lesson->delete();

        return redirect()
            ->route('mentor.courses.sessions.edit', $courseId)
            ->with('success', 'Lesson berhasil dihapus.');
    }

    private function storeMaterials(Request $request, Lesson $lesson): void
    {
        if ($request->hasFile('material_file')) {
            $file = $request->file('material_file');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension === 'pdf') {
                $type = 'pdf';
                $filePath = $file->store('lesson-pdfs', 'public');
            } else {
                $type = 'video';
                $filePath = $file->store('lesson-videos', 'public');
            }

            LessonMaterial::create([
                'lesson_id' => $lesson->id,
                'type' => $type,
                'file_path' => $filePath,
                'url' => null,
            ]);
        }

        if ($request->filled('material_url')) {
            $originalUrl = $request->material_url;
            $url = $this->convertToEmbedUrl($originalUrl);

            LessonMaterial::create([
                'lesson_id' => $lesson->id,
                'type' => $this->isYoutubeUrl($originalUrl) ? 'video' : 'link',
                'file_path' => null,
                'url' => $url,
            ]);
        }
    }

    private function isLastSession(Session $session): bool
    {
        $lastSession = $session->course->sessions()
            ->latest('id')
            ->first();

        return $lastSession && $lastSession->id === $session->id;
    }

    private function isYoutubeUrl(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        return Str::contains($url, [
            'youtube.com',
            'youtu.be',
        ]);
    }

    private function convertToEmbedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (Str::contains($url, 'youtube.com/watch?v=')) {
            $videoId = Str::after($url, 'watch?v=');
            $videoId = Str::before($videoId, '&');

            return 'https://www.youtube.com/embed/' . $videoId;
        }

        if (Str::contains($url, 'youtu.be/')) {
            $videoId = Str::after($url, 'youtu.be/');
            $videoId = Str::before($videoId, '?');

            return 'https://www.youtube.com/embed/' . $videoId;
        }

        if (Str::contains($url, 'youtube.com/shorts/')) {
            $videoId = Str::after($url, 'youtube.com/shorts/');
            $videoId = Str::before($videoId, '?');

            return 'https://www.youtube.com/embed/' . $videoId;
        }

        return $url;
    }
}