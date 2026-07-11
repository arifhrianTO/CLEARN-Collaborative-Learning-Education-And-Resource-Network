<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'progress',
        'completed_lessons',
        'start_date',
    ];

    protected $casts = [
        'start_date'        => 'datetime',
        'progress'          => 'integer',
        'completed_lessons' => 'array',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function rate()
    {
        return $this->hasOne(Rate::class);
    }

    public function exerciseAttempts()
    {
        return $this->hasMany(ExerciseAttempt::class);
    }

    public function exerciseResults()
    {
        return $this->hasMany(ExerciseResult::class);
    }

    public function finalProjectResults()
    {
        return $this->hasMany(FinalProjectResult::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    /**
     * Hitung ulang dan simpan progres enrollment berdasarkan urutan materi, latihan, dan tugas akhir.
     */
    public function recalculateAndSaveProgress()
    {
        $this->loadMissing(['course.sessions.lessons', 'course.sessions.exercises', 'course.sessions.finalProjects', 'exerciseAttempts', 'finalProjectResults']);

        $course = $this->course;
        if (!$course) return;

        // 1. Hitung Poin Materi (Maks 10%)
        $totalLessons = $course->sessions->flatMap->lessons->count();
        $completedLessonsCount = is_array($this->completed_lessons) ? count($this->completed_lessons) : 0;
        
        $lessonProgress = 0;
        if ($totalLessons > 0) {
            $lessonProgress = ($completedLessonsCount / $totalLessons) * 10;
        }

        // 2. Hitung Poin Latihan (Maks 30%)
        $totalExercises = $course->sessions->flatMap->exercises->count() ?? 0;
        // Jika tidak ada latihan di kursus, mungkin bobotnya bisa dipindah. Namun asumsi kita tetap 0/0 jika tidak ada.
        $exerciseProgress = 0;
        if ($totalExercises > 0) {
            // Cek berapa banyak exercise yang punya attempt
            $attemptedExercisesCount = $this->exerciseAttempts->pluck('exercise_id')->unique()->count();
            $exerciseProgress = ($attemptedExercisesCount / $totalExercises) * 30;
        } else {
            // Opsional: Jika tidak ada kuis, kasih gratis 30% supaya bisa lulus 100%
            $exerciseProgress = 30; 
        }

        // 3. Hitung Poin Tugas Akhir (Maks 60%)
        $totalFinalProjects = $course->sessions->flatMap->finalProjects->count();
        $finalProjectProgress = 0;
        
        if ($totalFinalProjects > 0) {
            // Karena asumsinya hanya 1 final project per kursus, ambil yang pertama
            $fpResult = $this->finalProjectResults->first();
            
            if ($fpResult) {
                if ($fpResult->final_project_score !== null && $fpResult->final_project_score >= 70) {
                    // Sudah dinilai dan Lulus
                    $finalProjectProgress = 60;
                } elseif ($fpResult->final_project_score === null && $fpResult->submission_file) {
                    // Sudah submit, belum dinilai
                    $finalProjectProgress = 30;
                } else {
                    // Jika final_project_score < 70 (Tidak Lulus / Dikembalikan)
                    $finalProjectProgress = 0;
                }
            }
        }

        // Hitung total progres baru
        $newProgress = round($lessonProgress + $exerciseProgress + $finalProjectProgress);

        // Pastikan nilainya maksimal 100
        $newProgress = min(100, $newProgress);

        // Hanya update jika progres baru lebih besar atau sama
        if ($newProgress > $this->progress) {
            $this->update(['progress' => $newProgress]);
        }
    }
}
