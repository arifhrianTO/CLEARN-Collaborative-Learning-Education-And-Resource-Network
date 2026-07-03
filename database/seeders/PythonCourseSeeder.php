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

class PythonCourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil atau buat Mentor & Admin
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $mentor = User::where('role', 'mentor')->where('email', 'alex@clearn.com')->first();
        if (!$mentor) {
             $mentor = User::create([
                'name' => 'Alex Developer',
                'email' => 'alex@clearn.com',
                'password' => bcrypt('password123'),
                'role' => 'mentor',
                'occupation' => 'Fullstack Developer',
            ]);
        }

        // 2. Dapatkan atau buat Kategori "Data Science"
        $category = Category::firstOrCreate(
            ['category_name' => 'Data Science'],
            [
                'admin_id' => $admin->id,
                'category_icon' => 'fa-database',
                'category_description' => 'Materi seputar pengolahan data, machine learning, dan analisis dengan Python.',
                'category_color' => '#10B981', // Emerald
            ]
        );

        // 3. Buat Course
        $course = Course::where('course_slug', Str::slug('Pemrograman Python untuk Data Science dari Nol'))->first();
        if ($course) {
            return; // Course sudah ada, hentikan seeder
        }

        $course = Course::create([
            'mentor_id' => $mentor->id,
            'category_id' => $category->id,
            'course_title' => 'Pemrograman Python untuk Data Science dari Nol',
            'course_slug' => Str::slug('Pemrograman Python untuk Data Science dari Nol'),
            'course_description' => 'Pelajari bahasa pemrograman Python dari dasar hingga penerapannya dalam analisis data menggunakan library populer seperti Pandas, NumPy, dan Matplotlib.',
            'course_thumbnail' => 'thumbnails/python-data-science-course.png', // Pastikan gambar ini ada atau ganti dengan default
            'course_price' => 250000.00,
            'status_publish' => 'published',
            'status_review' => 'approved',
        ]);

        // ==========================================
        // MODUL 1: Dasar-dasar Python
        // ==========================================
        $session1 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 1: Dasar-dasar Python',
            'sessions_description' => 'Mengenal sintaks dasar Python, tipe data, variabel, dan struktur kontrol.',
        ]);

        $lesson1_1 = Lesson::create([
            'sessions_id' => $session1->id,
            'lessons_title' => 'Instalasi Python dan Jupyter Notebook',
            'lessons_description' => 'Cara menginstal Anaconda distribution dan menjalankan Jupyter Notebook untuk pertama kalinya.',
        ]);

        LessonMaterial::create([
            'lesson_id' => $lesson1_1->id,
            'type' => 'video',
            'url' => 'https://www.youtube.com/watch?v=kQtD5dpn9CE', 
        ]);

        $lesson1_2 = Lesson::create([
            'sessions_id' => $session1->id,
            'lessons_title' => 'Tipe Data dan Variabel',
            'lessons_description' => 'Mempelajari tipe data primitif (integer, float, string, boolean) dan aturan penamaan variabel di Python.',
        ]);

        LessonMaterial::create([
            'lesson_id' => $lesson1_2->id,
            'type' => 'pdf',
            'file_path' => 'materials/python_datatypes.pdf',
        ]);

        // Kuis Modul 1
        $exercise1 = Exercise::create([
            'sessions_id' => $session1->id,
            'exercise_title' => 'Kuis Sintaks Dasar Python',
        ]);

        $q1 = Question::create([
            'exercise_id' => $exercise1->id,
            'question_text' => 'Fungsi apa yang digunakan untuk menampilkan output ke layar di Python?',
        ]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'echo()', 'is_correct' => false]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'print()', 'is_correct' => true]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'display()', 'is_correct' => false]);

        $q2 = Question::create([
            'exercise_id' => $exercise1->id,
            'question_text' => 'Simbol apa yang digunakan untuk membuat komentar dalam Python?',
        ]);
        Option::create(['question_id' => $q2->id, 'option_text' => '//', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => '/*', 'is_correct' => false]);
        Option::create(['question_id' => $q2->id, 'option_text' => '#', 'is_correct' => true]);


        // ==========================================
        // MODUL 2: Struktur Data dan Fungsi
        // ==========================================
        $session2 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 2: Struktur Data dan Fungsi',
            'sessions_description' => 'Memahami struktur data bawaan Python (List, Tuple, Dictionary, Set) dan cara membuat fungsi kustom.',
        ]);

        $lesson2_1 = Lesson::create([
            'sessions_id' => $session2->id,
            'lessons_title' => 'Bekerja dengan List dan Dictionary',
            'lessons_description' => 'Cara menyimpan, mengakses, dan memanipulasi sekumpulan data menggunakan List dan Dictionary.',
        ]);

        $lesson2_2 = Lesson::create([
            'sessions_id' => $session2->id,
            'lessons_title' => 'Membuat dan Menggunakan Fungsi',
            'lessons_description' => 'Konsep DRY (Don\'t Repeat Yourself) dengan memecah kode menjadi blok-blok fungsi menggunakan kata kunci def.',
        ]);


        // ==========================================
        // MODUL 3: Pandas & Pengenalan Data Science
        // ==========================================
        $session3 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 3: Pandas & Pengenalan Data Science',
            'sessions_description' => 'Mulai menggunakan library Pandas untuk membaca dataset, membersihkan data, dan melakukan eksplorasi data dasar.',
        ]);

        $lesson3_1 = Lesson::create([
            'sessions_id' => $session3->id,
            'lessons_title' => 'Pengenalan Pandas DataFrame',
            'lessons_description' => 'Apa itu Series dan DataFrame? Cara membuat dan memanipulasi struktur data tabular 2 dimensi dengan Pandas.',
        ]);

        $lesson3_2 = Lesson::create([
            'sessions_id' => $session3->id,
            'lessons_title' => 'Membaca Dataset CSV dan Data Cleaning Dasar',
            'lessons_description' => 'Menggunakan pd.read_csv() untuk memuat data riil, menangani missing values (NaN), dan menghapus duplikasi.',
        ]);
        
        LessonMaterial::create([
            'lesson_id' => $lesson3_2->id,
            'type' => 'zip',
            'file_path' => 'materials/sample_dataset.zip',
        ]);

        // ==========================================
        // MODUL 4: Tugas Akhir (Harus di session terpisah berdasarkan aturan baru)
        // ==========================================
        $session4 = Session::create([
            'course_id' => $course->id,
            'sessions_title' => 'Modul 4: Proyek Analisis Data',
            'sessions_description' => 'Menerapkan semua pengetahuan dari modul-modul sebelumnya untuk menganalisis dataset nyata.',
        ]);

        FinalProject::create([
            'sessions_id' => $session4->id,
            'project_title' => 'Analisis Dataset Penjualan Supermarket',
            'project_description' => 'Anda diberikan dataset penjualan supermarket (supermarket_sales.csv). Tugas Anda adalah: 1. Bersihkan data dari missing values jika ada. 2. Temukan cabang (Branch) dengan total penjualan (Total) tertinggi. 3. Buat program Python (Jupyter Notebook) yang menampilkan hasil analisis tersebut. Kumpulkan file .ipynb Anda dalam bentuk ZIP.',
            'duration_days' => 10,
            'allowed_extensions' => '.zip,.rar',
        ]);
    }
}