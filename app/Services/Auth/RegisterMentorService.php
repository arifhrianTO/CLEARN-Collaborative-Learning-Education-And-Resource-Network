<?php

namespace App\Services\Auth;

use App\Models\DetailVerify;
use App\Models\ProfileAccount;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterMentorService
{
    public function handle(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $profilePath = $this->storeFile($data['profile_picture'] ?? null, 'profile/mentor');

            $user = User::create([
                'name'            => $data['name'],
                'email'           => $data['email'],
                'profile_picture' => $profilePath,
                'password'        => Hash::make($data['password']),
                'role'            => 'mentor',
                'status'          => 'pending',
                'occupation'      => $data['expertise'] ?? null,
            ]);

            ProfileAccount::create([
                'user_id'          => $user->id,
                'front_title'      => $data['front_title'] ?? null,
                'back_title'       => $data['back_title'] ?? null,
                'expertise'        => $data['expertise'] ?? null,
                'bio'              => $data['bio'] ?? null,
                'linkedin_link'    => $data['linkedin_link'] ?? null,
                'scopus_link'      => $data['scopus_link'] ?? null,
                'sinta_link'       => $data['sinta_link'] ?? null,
                'certificate_file' => $this->storeFile($data['certificate_file'] ?? null, 'certificates'),
                'diploma_file'     => $this->storeFile($data['ijazah'] ?? null, 'diploma_file'),
                'cv_file'          => $this->storeFile($data['cv'] ?? null, 'cv_file'),
            ]);

            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);

            DetailVerify::create([
                'admin_id' => null,
                'mentor_id' => $user->id,
                'action' => 'pending',
                'mentor_rejection_reason' => null,
                'verify_at' => null,
            ]);

            event(new Registered($user));

            return $user;
        });
    }

    private function storeFile(mixed $file, string $folder): ?string
    {
        return $file instanceof UploadedFile ? $file->store($folder, 'public') : null;
    }
}
