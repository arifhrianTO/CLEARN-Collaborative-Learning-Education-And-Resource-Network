<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProfileAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan direktori di storage/app/public/ sudah ada
        Storage::disk('public')->makeDirectory('avatars');
        Storage::disk('public')->makeDirectory('cv_file');
        Storage::disk('public')->makeDirectory('certificates');
        Storage::disk('public')->makeDirectory('diploma_file');

        // Data 6 Mentor Realistis
        $mentorsData = [
            ['id' => 'arif',  'name' => 'Muhammad Arif Harianto', 'email' => 'arif@gmail.com',  'expertise' => 'Data Science & Machine Learning'],
            ['id' => 'alya',  'name' => 'Alya Ghaitsa Salsabila',  'email' => 'alya@gmail.com',  'expertise' => 'UI/UX Design & Illustration'],
            ['id' => 'rivo',  'name' => 'Rivo Nyawan Situmorang ',  'email' => 'rivo@gmail.com',  'expertise' => 'Full-Stack Web Development'],
            ['id' => 'aysel', 'name' => 'Aysel Putra Ardiansyah',  'email' => 'aysel@gmail.com', 'expertise' => 'Digital Marketing & SEO'],
            ['id' => 'fai',   'name' => 'Nayla Fairuz Nur Azizah',  'email' => 'fai@gmail.com',   'expertise' => 'Mobile App Development'],
            ['id' => 'asa',   'name' => 'Hasanun Nisa',   'email' => 'asa@gmail.com',   'expertise' => 'Business Strategy & Management'],
        ];

        // Path untuk Dokumen (CV, Sertifikat, Diploma) -> Tetap 1 file per jenis untuk semua
        $sourceCv          = database_path('seeders/assets/cv/dummy.pdf');
        $sourceCertificate = database_path('seeders/assets/certificates/dummy.pdf');
        $sourceDiploma     = database_path('seeders/assets/diploma/dummy.pdf');

        $cvPath = $certificatePath = $diplomaPath = null;

        if (File::exists($sourceCv)) {
            File::copy($sourceCv, storage_path('app/public/cv_file/dummy_cv.pdf'));
            $cvPath = 'cv_file/dummy_cv.pdf';
        }
        if (File::exists($sourceCertificate)) {
            File::copy($sourceCertificate, storage_path('app/public/certificates/dummy_certificate.pdf'));
            $certificatePath = 'certificates/dummy_certificate.pdf';
        }
        if (File::exists($sourceDiploma)) {
            File::copy($sourceDiploma, storage_path('app/public/diploma_file/dummy_diploma.pdf'));
            $diplomaPath = 'diploma_file/dummy_diploma.pdf';
        }

        $manager = new ImageManager(new Driver());

        foreach ($mentorsData as $data) {
            // Cek spesifik Foto Profil Mentor (dengan format nama sesuai upload Anda: nama.JPG)
            $sourceProfile = database_path("seeders/assets/profile/{$data['id']}.JPG");
            $profilePath = null;

            if (File::exists($sourceProfile)) {
                $filename = "mentor_{$data['id']}.jpg";
                $destPath = 'avatars/' . $filename;

                // Proses gambar dengan Intervention v4
                $image = $manager->decodePath($sourceProfile);
                $image->cover(300, 300);
                $encoded = $image->encodeUsingMediaType('image/jpeg', quality: 75);

                Storage::disk('public')->put($destPath, (string) $encoded);
                $profilePath = $destPath;
            }

            // Bangkitkan User Mentor
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'            => $data['name'],
                    'password'        => Hash::make('password123'),
                    'role'            => 'mentor',
                    'status'          => 'active',
                    'occupation'      => $data['expertise'],
                    'profile_picture' => $profilePath,
                ]
            );

            // Bangkitkan Profile Account Mentor
            ProfileAccount::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'bio'              => "Halo, saya {$data['name']}. Saya adalah praktisi berpengalaman di bidang {$data['expertise']} yang berdedikasi untuk membantu siswa memahami materi dengan cara terbaik dan aplikatif.",
                    'expertise'        => $data['expertise'],
                    'front_title'      => 'Dr.',
                    'back_title'       => 'S.Kom., M.T.',
                    'linkedin_link'    => "https://linkedin.com/in/{$data['id']}",
                    'cv_file'          => $cvPath,
                    'certificate_file' => $certificatePath,
                    'diploma_file'     => $diplomaPath,
                ]
            );
        }
    }
}
