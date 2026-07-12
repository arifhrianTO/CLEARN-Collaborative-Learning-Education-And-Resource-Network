<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kita mengambil admin_id pertama (Super Admin)
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->error('Admin belum dibuat! Silakan jalankan AdminSeeder terlebih dahulu.');
            return;
        }

        $categories = [
            [
                'category_name' => 'Pemrograman & IT',
                'category_icon' => 'fa-solid fa-code',
                'category_description' => 'Pelajari bahasa pemrograman, arsitektur software, dan teknologi informasi terbaru.',
                'category_color' => '#3b82f6', // Biru
            ],
            [
                'category_name' => 'Desain Grafis & UI/UX',
                'category_icon' => 'fa-solid fa-pen-nib',
                'category_description' => 'Asah kreativitas Anda dalam membuat desain antarmuka, logo, dan ilustrasi digital.',
                'category_color' => '#ec4899', // Pink
            ],
            [
                'category_name' => 'Bisnis & Pemasaran',
                'category_icon' => 'fa-solid fa-chart-line',
                'category_description' => 'Strategi bisnis, digital marketing, SEO, dan cara meningkatkan penjualan Anda.',
                'category_color' => '#f59e0b', // Kuning/Amber
            ],
            [
                'category_name' => 'Sains Data & AI',
                'category_icon' => 'fa-solid fa-robot',
                'category_description' => 'Eksplorasi big data, machine learning, kecerdasan buatan, dan analisis statistik.',
                'category_color' => '#10b981', // Hijau (Emerald)
            ]
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['category_name' => $cat['category_name']],
                [
                    'admin_id'             => $admin->id,
                    'category_icon'        => $cat['category_icon'],
                    'category_description' => $cat['category_description'],
                    'category_color'       => $cat['category_color'],
                ]
            );
        }
    }
}
