<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FinalProject;
use App\Models\FinalProjectResult;
use App\Models\User;
use Illuminate\Database\Seeder;

class PenilaianTestSeeder extends Seeder
{
    public function run(): void
    {
        // Cari kursus yang punya final project
        $courses = Course::whereHas('sessions.finalProjects')->with('sessions.finalProjects')->get();

        if ($courses->isEmpty()) {
            $this->command->error('Tidak ada kursus dengan final project!');
            return;
        }

        $course = $courses->first();
        $finalProject = null;
        foreach ($course->sessions as $session) {
            if ($session->finalProjects->isNotEmpty()) {
                $finalProject = $session->finalProjects->first();
                break;
            }
        }
        if (!$finalProject) {
            $this->command->error('Tidak ada final project di kursus ini!');
            return;
        }

        // Cari atau buat student
        $student = User::firstOrCreate(
            ['email' => 'student_test@example.com'],
            [
                'name' => 'Student Test',
                'password' => bcrypt('password123'),
                'role' => 'student',
                'status' => 'active',
            ]
        );

        // Cari atau buat enrollment
        $enrollment = Enrollment::firstOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course->id,
            ],
            [
                'progress' => 80,
                'start_date' => now()->subDays(7),
            ]
        );

        // Buat FinalProjectResult (pending, null score)
        $result = FinalProjectResult::firstOrCreate(
            [
                'final_project_id' => $finalProject->id,
                'enrollment_id' => $enrollment->id,
            ],
            [
                'final_project_score' => null,
                'submission_file' => 'projects/test_submission.zip',
            ]
        );

        $this->command->info('Data test penilaian berhasil dibuat:');
        $this->command->info("  Mentor: {$course->mentor->email}");
        $this->command->info("  Course: {$course->course_title}");
        $this->command->info("  Student: {$student->email}");
        $this->command->info("  Enrollment ID: {$enrollment->id}");
        $this->command->info("  FinalProjectResult ID: {$result->id} (score: null = pending)");
    }
}
