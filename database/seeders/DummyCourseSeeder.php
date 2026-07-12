<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Session;
use App\Models\Lesson;
use App\Models\FinalProject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DummyCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('course-thumbnails');

        // Ambil kategori untuk referensi
        $categoryProgramming = Category::where('category_name', 'Pemrograman & IT')->first();
        $categoryDesign      = Category::where('category_name', 'Desain Grafis & UI/UX')->first();
        $categoryBusiness    = Category::where('category_name', 'Bisnis & Pemasaran')->first();
        $categoryData        = Category::where('category_name', 'Sains Data & AI')->first();

        if (!$categoryProgramming || !$categoryDesign || !$categoryBusiness || !$categoryData) {
            $this->command->error('Data Kategori belum ada. Pastikan CategorySeeder sudah dijalankan.');
            return;
        }

        // Data 12 Kursus Realistis menggunakan gambar Anda
        $coursesData = [
            // Mentor Arif (Data Science)
            ['mentor_email' => 'arif@clearn.com', 'image' => 'py.png', 'title' => 'Mastering Python untuk Data Science', 'category_id' => $categoryData->id, 'price' => 250000],
            ['mentor_email' => 'arif@clearn.com', 'image' => 'machine learning.jpg', 'title' => 'Machine Learning untuk Pemula', 'category_id' => $categoryData->id, 'price' => 175000],
            
            // Mentor Alya (Design)
            ['mentor_email' => 'alya@clearn.com', 'image' => 'figma.png', 'title' => 'UI/UX Design dengan Figma', 'category_id' => $categoryDesign->id, 'price' => 150000],
            ['mentor_email' => 'alya@clearn.com', 'image' => 'canva.jpg', 'title' => 'Desain Sosial Media dengan Canva', 'category_id' => $categoryDesign->id, 'price' => 200000],
            
            // Mentor Rivo (Web Dev)
            ['mentor_email' => 'rivo@clearn.com', 'image' => 'course-laravel.png', 'title' => 'Full-Stack Web Developer dengan Laravel', 'category_id' => $categoryProgramming->id, 'price' => 300000],
            ['mentor_email' => 'rivo@clearn.com', 'image' => 'node js.png', 'title' => 'Membangun REST API dengan Node.js', 'category_id' => $categoryProgramming->id, 'price' => 220000],
            
            // Mentor Aysel (Marketing)
            ['mentor_email' => 'aysel@clearn.com', 'image' => 'digital marketing.jpg', 'title' => 'Strategi Digital Marketing 2026', 'category_id' => $categoryBusiness->id, 'price' => 100000],
            ['mentor_email' => 'aysel@clearn.com', 'image' => 'seo.jpg', 'title' => 'SEO Mastery untuk Website Baru', 'category_id' => $categoryBusiness->id, 'price' => 125000],
            
            // Mentor Fai (Mobile Dev)
            ['mentor_email' => 'fai@clearn.com', 'image' => 'flutter.jpg', 'title' => 'Flutter Masterclass: Buat Aplikasi Android & iOS', 'category_id' => $categoryProgramming->id, 'price' => 350000],
            ['mentor_email' => 'fai@clearn.com', 'image' => 'kotlin.png', 'title' => 'Pemrograman Kotlin dari Nol', 'category_id' => $categoryProgramming->id, 'price' => 150000],
            
            // Mentor Asa (Business)
            ['mentor_email' => 'asa@clearn.com', 'image' => 'bussines analyst.jpg', 'title' => 'Business Analytics & Strategy', 'category_id' => $categoryBusiness->id, 'price' => 250000],
            ['mentor_email' => 'asa@clearn.com', 'image' => 'finance.jpg', 'title' => 'Manajemen Keuangan untuk Startup', 'category_id' => $categoryBusiness->id, 'price' => 180000],
        ];

        $manager = new ImageManager(new Driver());

        foreach ($coursesData as $c) {
            $mentor = User::where('email', $c['mentor_email'])->first();
            if (!$mentor) continue;

            // Proses Thumbnail Kursus per file dengan Intervention Image
            $sourceThumbnail = database_path("seeders/assets/thumbnails/{$c['image']}");
            $thumbnailPath = null;
            
            if (File::exists($sourceThumbnail)) {
                // Buat nama baru agar format selalu jpg (krn dikompres)
                $safeName = Str::slug($c['title']) . '.jpg';
                $destPath = 'course-thumbnails/' . $safeName;
                
                // Proses gambar
                $image = $manager->decodePath($sourceThumbnail);
                $image->cover(800, 450);
                $encoded = $image->encodeUsingMediaType('image/jpeg', quality: 75);
                
                Storage::disk('public')->put($destPath, (string) $encoded);
                $thumbnailPath = $destPath;
            }

            // Bangkitkan Kursus
            $course = Course::firstOrCreate(
                ['course_slug' => Str::slug($c['title']) . '-' . rand(1000, 9999)],
                [
                    'mentor_id'          => $mentor->id,
                    'category_id'        => $c['category_id'],
                    'course_title'       => $c['title'],
                    'course_description' => "Selamat datang di kursus {$c['title']}. Di sini Anda akan dibimbing langsung oleh {$mentor->name} dari tingkat dasar hingga mahir dengan materi yang komprehensif dan tugas praktik yang relevan dengan industri saat ini.",
                    'course_thumbnail'   => $thumbnailPath,
                    'course_price'       => $c['price'],
                    'status_publish'     => 'published',
                    'status_review'      => 'approved',
                ]
            );

            // Bangkitkan 5 Pertemuan (Sesi)
            for ($s = 1; $s <= 5; $s++) {
                $session = Session::create([
                    'course_id'            => $course->id,
                    'sessions_title'       => "Modul $s: Pembahasan Bagian " . ['Satu', 'Dua', 'Tiga', 'Empat', 'Lima'][$s-1],
                    'sessions_description' => "Penjelasan mendalam untuk modul ke-$s. Silakan pelajari materi dengan saksama.",
                ]);

                // Tambahkan 1 Lesson kosong
                Lesson::create([
                    'sessions_id'         => $session->id,
                    'lessons_title'       => "Materi Inti Modul $s",
                    'lessons_description' => "Teks materi kosong. Mentor akan mengisinya nanti dengan studi kasus dan panduan.",
                ]);
            }

            // Tambahkan 1 Final Project di akhir
            $lastSession = $course->sessions()->latest('id')->first();
            if ($lastSession) {
                FinalProject::create([
                    'sessions_id'         => $lastSession->id,
                    'project_title'       => "Tugas Akhir: Evaluasi Portofolio",
                    'project_description' => "Buatlah sebuah proyek lengkap berdasarkan semua modul yang telah Anda pelajari. Ini adalah syarat utama kelulusan Anda.",
                    'duration_days'       => 7, // Waktu pengerjaan 7 hari
                    'allowed_extensions'  => 'zip,rar,pdf',
                ]);
            }
        }
    }
}
