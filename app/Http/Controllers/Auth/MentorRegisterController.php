<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DetailVerify;
use App\Models\ProfileAccount;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MentorRegisterController extends Controller
{
    /**
     * Aturan validasi untuk registrasi mentor.
     */
    protected function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:255'],
            'front_title'      => ['nullable', 'string', 'max:255'],
            'back_title'       => ['nullable', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
            'expertise'        => ['nullable', 'string', 'max:255'],
            'bio'              => ['nullable', 'string'],
            'linkedin_link'    => ['nullable', 'url'],
            'scopus_link'      => ['nullable', 'url'],
            'sinta_link'       => ['nullable', 'url'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'ijazah'           => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'cv'               => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'profile_picture'  => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];
    }

    public function showForm(): View
    {
        return view('auth.register-mentor');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($data) {
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
                'admin_id'                => null,
                'mentor_id'               => $user->id,
                'action'                  => 'pending',
                'mentor_rejection_reason' => null,
                'verify_at'               => null,
            ]);

            event(new Registered($user));
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * Simpan file upload ke storage, kembalikan path-nya.
     */
    private function storeFile(mixed $file, string $folder): ?string
    {
        return $file instanceof UploadedFile ? $file->store($folder, 'public') : null;
    }
}
