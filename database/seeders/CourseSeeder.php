<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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
        $course1 = Course::where('course_slug', Str::slug('Membuat Website Modern dengan Laravel dan Tailwind CSS'))->first();
        if (!$course1) {
            
            $sourceThumbnail1 = database_path('seeders/assets/thumbnails/course-laravel.png');
            $dbThumbnailPath1 = 'thumbnails/default-course.png'; 

            if (File::exists($sourceThumbnail1)) {
                $fileName1 = 'laravel-thumbnail-' . time() . '.png';
                $destinationPath1 = 'course-thumbnails/' . $fileName1;
                Storage::disk('public')->put($destinationPath1, File::get($sourceThumbnail1));
                $dbThumbnailPath1 = $destinationPath1;
            }

            $course1 = Course::create([
                'mentor_id' => $mentor->id,
                'category_id' => $category->id,
                'course_title' => 'Membuat Website Modern dengan Laravel dan Tailwind CSS',
                'course_slug' => Str::slug('Membuat Website Modern dengan Laravel dan Tailwind CSS'),
                'course_description' => 'Pelajari cara membangun website modern, responsif, dan dinamis dari nol menggunakan PHP Framework terpopuler (Laravel) dan Utility-First CSS Framework (Tailwind CSS).',
                'course_thumbnail' => $dbThumbnailPath1,
                'course_price' => 199000.00,
                'status_publish' => 'published',
                'status_review' => 'approved',
            ]);

            // ==========================================
            // MODUL 1: Persiapan dan Instalasi
            // ==========================================
            $session1 = Session::create([
                'course_id' => $course1->id,
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
            
            $sourcePdf1 = database_path('seeders/assets/lessons/dummy_materi.pdf');
            $dbPdfPath1 = 'materials/laravel_setup_guide.pdf';
            if(File::exists($sourcePdf1)) {
                $fileNamePdf1 = 'laravel_setup_' . time() . '.pdf';
                $destPdf1 = 'lesson-pdfs/' . $fileNamePdf1;
                Storage::disk('public')->put($destPdf1, File::get($sourcePdf1));
                $dbPdfPath1 = $destPdf1;
            }

            LessonMaterial::create([
                'lesson_id' => $lesson1_2->id,
                'type' => 'pdf',
                'file_path' => $dbPdfPath1,
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
                'course_id' => $course1->id,
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

            $sourceZip1 = database_path('seeders/assets/lessons/dummy_materi.zip');
            $dbZipPath1 = 'materials/landing_page_tailwind_code.zip';
            if(File::exists($sourceZip1)) {
                $fileNameZip1 = 'landing_page_tailwind_code_' . time() . '.zip';
                $destZip1 = 'final-project-materials/' . $fileNameZip1; 
                Storage::disk('public')->put($destZip1, File::get($sourceZip1));
                $dbZipPath1 = $destZip1;
            }

            LessonMaterial::create([
                'lesson_id' => $lesson2_2->id,
                'type' => 'zip',
                'file_path' => $dbZipPath1,
            ]);


            // ==========================================
            // MODUL 3: Backend & Database (Laravel Action)
            // ==========================================
            $session3 = Session::create([
                'course_id' => $course1->id,
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
        
        // 4. Buat Course Baru (Course 2)
        $course2 = Course::where('course_slug', Str::slug('Mastering Vue JS 3 Composition API'))->first();
        if(!$course2) {
            
            $sourceThumbnail2 = database_path('seeders/assets/thumbnails/course-vue.png');
            $dbThumbnailPath2 = 'thumbnails/default-course.png'; 

            if (File::exists($sourceThumbnail2)) {
                $fileName2 = 'vue-thumbnail-' . time() . '.png';
                $destinationPath2 = 'course-thumbnails/' . $fileName2;
                Storage::disk('public')->put($destinationPath2, File::get($sourceThumbnail2));
                $dbThumbnailPath2 = $destinationPath2;
            }

            $course2 = Course::create([
                'mentor_id' => $mentor->id,
                'category_id' => $category->id,
                'course_title' => 'Mastering Vue JS 3 Composition API',
                'course_slug' => Str::slug('Mastering Vue JS 3 Composition API'),
                'course_description' => 'Belajar Vue JS versi terbaru menggunakan Composition API, Pinia untuk state management, dan Vue Router untuk membangun SPA (Single Page Application) yang powerful.',
                'course_thumbnail' => $dbThumbnailPath2,
                'course_price' => 250000.00,
                'status_publish' => 'published',
                'status_review' => 'approved',
            ]);

            $session4 = Session::create([
                'course_id' => $course2->id,
                'sessions_title' => 'Modul 1: Pengenalan Vue 3 dan Composition API',
                'sessions_description' => 'Mempelajari dasar-dasar Vue 3 dan perbedaan antara Options API dengan Composition API yang baru.',
            ]);

            $lesson4_1 = Lesson::create([
                'sessions_id' => $session4->id,
                'lessons_title' => 'Setup Project dengan Vite dan Vue',
                'lessons_description' => 'Cara membuat project baru Vue 3 menggunakan build tool super cepat, Vite.',
            ]);
            
            $sourcePdf2 = database_path('seeders/assets/lessons/dummy_materi.pdf');
            $dbPdfPath2 = 'materials/vue_setup.pdf';
            if(File::exists($sourcePdf2)) {
                $fileNamePdf2 = 'vue_setup_' . time() . '.pdf';
                $destPdf2 = 'lesson-pdfs/' . $fileNamePdf2;
                Storage::disk('public')->put($destPdf2, File::get($sourcePdf2));
                $dbPdfPath2 = $destPdf2;
            }

            LessonMaterial::create([
                'lesson_id' => $lesson4_1->id,
                'type' => 'pdf',
                'file_path' => $dbPdfPath2,
            ]);

            // Final Project untuk Course 2
            FinalProject::create([
                'sessions_id' => $session4->id,
                'project_title' => 'Membangun ToDo App Menggunakan Vue 3 Composition API',
                'project_description' => 'Buatlah aplikasi ToDo List sederhana menggunakan Vue 3 dengan Composition API. Aplikasi harus memiliki fitur untuk menambah, menandai selesai, dan menghapus tugas. Pastikan menggunakan Tailwind CSS untuk styling. Kompres folder proyek (tanpa node_modules) menjadi file ZIP/RAR dan kumpulkan.',
                'duration_days' => 5,
                'allowed_extensions' => 'zip,rar',
            ]);
        }

        // 5. Buat Course Baru (Course 3)
        $course3 = Course::where('course_slug', Str::slug('React JS untuk Pemula'))->first();
        if(!$course3) {
            
            $sourceThumbnail3 = database_path('seeders/assets/thumbnails/course-react.png');
            $dbThumbnailPath3 = 'thumbnails/default-course.png'; 

            if (File::exists($sourceThumbnail3)) {
                $fileName3 = 'react-thumbnail-' . time() . '.png';
                $destinationPath3 = 'course-thumbnails/' . $fileName3;
                Storage::disk('public')->put($destinationPath3, File::get($sourceThumbnail3));
                $dbThumbnailPath3 = $destinationPath3;
            }

            $course3 = Course::create([
                'mentor_id' => $mentor->id,
                'category_id' => $category->id,
                'course_title' => 'React JS untuk Pemula',
                'course_slug' => Str::slug('React JS untuk Pemula'),
                'course_description' => 'Panduan komprehensif belajar React JS dari dasar. Memahami konsep komponen, state, props, dan hooks untuk membangun antarmuka web interaktif.',
                'course_thumbnail' => $dbThumbnailPath3,
                'course_price' => 225000.00,
                'status_publish' => 'published',
                'status_review' => 'approved',
            ]);

            $session5 = Session::create([
                'course_id' => $course3->id,
                'sessions_title' => 'Modul 1: Dasar-dasar React',
                'sessions_description' => 'Memahami fundamental React, termasuk JSX, Rendering Elements, Components, dan Props.',
            ]);

            $lesson5_1 = Lesson::create([
                'sessions_id' => $session5->id,
                'lessons_title' => 'Pengenalan JSX dan Components',
                'lessons_description' => 'Belajar menulis struktur UI menggunakan JSX dan memahami cara kerja komponen fungsional di React.',
            ]);
            
            $sourcePdf3 = database_path('seeders/assets/lessons/dummy_materi.pdf');
            $dbPdfPath3 = 'materials/react_basics.pdf';
            if(File::exists($sourcePdf3)) {
                $fileNamePdf3 = 'react_basics_' . time() . '.pdf';
                $destPdf3 = 'lesson-pdfs/' . $fileNamePdf3;
                Storage::disk('public')->put($destPdf3, File::get($sourcePdf3));
                $dbPdfPath3 = $destPdf3;
            }

            LessonMaterial::create([
                'lesson_id' => $lesson5_1->id,
                'type' => 'pdf',
                'file_path' => $dbPdfPath3,
            ]);

            // Final Project untuk Course 3
            FinalProject::create([
                'sessions_id' => $session5->id,
                'project_title' => 'Membuat Aplikasi Kalkulator dengan React',
                'project_description' => 'Bangun aplikasi kalkulator interaktif menggunakan React. Anda harus mengimplementasikan state (useState) untuk mengelola input dan hasil perhitungan. Desain kalkulator semenarik mungkin. Kumpulkan proyek dalam bentuk ZIP/RAR.',
                'duration_days' => 6,
                'allowed_extensions' => 'zip,rar',
            ]);
        }
    }
}
