<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Session;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\Option;
use App\Models\FinalProject;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil atau buat Mentor & Admin (karena Category butuh admin_id)
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $mentor = User::where('role', 'mentor')->first() ?? User::create([
            'name' => 'Alex Developer',
            'email' => 'alex@clearn.com',
            'password' => bcrypt('password123'),
            'role' => 'mentor',
            'occupation' => 'Fullstack Developer',
        ]);

        // 2. Dapatkan atau buat Kategori "Web Development"
        $category = Category::firstOrCreate(
            ['category_name' => 'Web Development'],
            [
                'admin_id' => $admin->id,
                'category_icon' => 'fa-code',
                'category_description' => 'Materi seputar pemrograman web, framework, dan UI/UX design.',
                'category_color' => '#4F46E5', // Indigo
            ]
        );

        // 3. Buat Course Utama (jika belum ada)
        $course = Course::where('course_slug', Str::slug('Membuat Website Modern dengan Laravel dan Tailwind CSS'))->first();
        if ($course) {
            return;
        }

        $course = Course::create([
            'mentor_id' => $mentor->id,
            'category_id' => $category->id,
            'course_title' => 'Membuat Website Modern dengan Laravel dan Tailwind CSS',
            'course_slug' => Str::slug('Membuat Website Modern dengan Laravel dan Tailwind CSS'),
            'course_description' => 'Pelajari cara membangun website modern, responsif, dan dinamis dari nol menggunakan PHP Framework terpopuler (Laravel) dan Utility-First CSS Framework (Tailwind CSS).',
            'course_thumbnail' => 'thumbnails/laravel-tailwind-course.png',
            'course_price' => 199000.00,
            'status_publish' => 'published',
            'status_review' => 'approved',
        ]);

        // ==========================================
        // MODUL 1: Persiapan dan Instalasi
        // ==========================================
        $session1 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 1: Persiapan dan Instalasi',
            'sessions_description' => 'Memahami konsep dasar MVC Laravel, kelebihan Tailwind CSS, dan melakukan instalasi tools yang diperlukan.',
        ]);

        // Lesson 1.1
        $lesson1_1 = Lesson::create([
            'sessions_id' => $session1->id,
            'lessons_title' => 'Pengenalan Laravel & Tailwind CSS',
            'lessons_description' => 'Mengapa memilih Laravel dan Tailwind CSS? Pertanyaan ini akan dijawab di materi pertama ini dengan melihat fitur-fitur unggulan kedua teknologi tersebut.',
        ]);

        LessonMaterial::create([
            'lesson_id' => $lesson1_1->id,
            'type' => 'video',
            'url' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE', // Link video tutorial pengenalan
        ]);

        // Lesson 1.2
        $lesson1_2 = Lesson::create([
            'sessions_id' => $session1->id,
            'lessons_title' => 'Setup Environment & Instalasi Laravel',
            'lessons_description' => 'Panduan lengkap setup XAMPP/Laragon, Composer, dan pembuatan project baru Laravel via Command Line.',
        ]);

        LessonMaterial::create([
            'lesson_id' => $lesson1_2->id,
            'type' => 'pdf',
            'file_path' => 'materials/laravel_setup_guide.pdf',
        ]);

        // Exercise Modul 1
        $exercise1 = Exercise::create([
            'sessions_id' => $session1->id,
            'exercise_title' => 'Kuis Instalasi & Dasar Laravel',
        ]);

        // Question 1
        $q1 = Question::create([
            'exercise_id' => $exercise1->id,
            'question_text' => 'Perintah PHP Artisan apa yang digunakan untuk menjalankan server lokal Laravel?',
        ]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'php artisan start', 'is_correct' => false]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'php artisan serve', 'is_correct' => true]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'php artisan run', 'is_correct' => false]);

        // Question 2
        $q2 = Question::create([
            'exercise_id' => $exercise1->id,
            'question_text' => 'Package manager apa yang wajib diinstal untuk memasang Laravel?',
        ]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'NPM', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'Pip', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => 'Composer', 'is_correct' => true]);


        // ==========================================
        // MODUL 2: Integrasi & Styling dengan Tailwind CSS
        // ==========================================
        $session2 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 2: Integrasi & Styling dengan Tailwind CSS',
            'sessions_description' => 'Mengintegrasikan Tailwind CSS ke dalam Laravel menggunakan Vite dan mendesain antarmuka halaman web secara interaktif.',
        ]);

        // Lesson 2.1
        $lesson2_1 = Lesson::create([
            'sessions_id' => $session2->id,
            'lessons_title' => 'Instalasi Tailwind CSS di Laravel via Vite',
            'lessons_description' => 'Bagaimana cara setup postcss, tailwind.config.js, serta directive @tailwind di file CSS agar terintegrasi sempurna dengan build tool Vite.',
        ]);

        // Lesson 2.2
        $lesson2_2 = Lesson::create([
            'sessions_id' => $session2->id,
            'lessons_title' => 'Membuat Halaman Landing Page Responsif',
            'lessons_description' => 'Praktik langsung mendesain Layout Hero section, Features, dan Footer menggunakan class utility Tailwind CSS agar responsif di mobile dan desktop.',
        ]);

        LessonMaterial::create([
            'lesson_id' => $lesson2_2->id,
            'type' => 'zip',
            'file_path' => 'materials/landing_page_tailwind_code.zip',
        ]);


        // ==========================================
        // MODUL 3: Backend & Database (Laravel Action)
        // ==========================================
        $session3 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 3: Backend & Database (Laravel Action)',
            'sessions_description' => 'Mempelajari backend Laravel mulai dari Routing, Controller, Blade View templating, hingga interaksi database menggunakan Eloquent ORM.',
        ]);

        // Lesson 3.1
        $lesson3_1 = Lesson::create([
            'sessions_id' => $session3->id,
            'lessons_title' => 'Routing, Controller, dan Blade Templating',
            'lessons_description' => 'Membuat route baru, memisahkan logika ke Controller, dan menyusun layout template Blade dengan master-layout/extends.',
        ]);

        // Lesson 3.2
        $lesson3_2 = Lesson::create([
            'sessions_id' => $session3->id,
            'lessons_title' => 'Database Migrations & Eloquent ORM',
            'lessons_description' => 'Membuat migration, mendefinisikan kolom, menjalankan artisan migrate, dan melakukan operasi CRUD (Create, Read, Update, Delete) data dengan Model Eloquent.',
        ]);

        // Final Project Modul 3
        FinalProject::create([
            'sessions_id' => $session3->id,
            'project_title' => 'Membangun Aplikasi Web Portofolio / Blog Sederhana',
            'project_description' => 'Buatlah proyek akhir berupa website portofolio pribadi atau blog sederhana yang dihubungkan ke database. Tampilan wajib dibuat menggunakan Tailwind CSS yang responsif, dan data dibaca menggunakan Laravel Eloquent. Kompres folder proyek Anda (tanpa folder vendor & node_modules) menjadi format ZIP/RAR lalu kumpulkan.',
            'duration_days' => 7,
            'allowed_extensions' => 'zip,rar',
        ]);
    }
}
