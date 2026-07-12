<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman settings / profil user.
     */
    public function edit(Request $request): View
    {
        return view('settings.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil (nama, email, foto).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi langsung di sini, menggantikan ProfileUpdateRequest
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'phone' => ['nullable', 'string', 'max:20'],
            'username' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-z0-9._]+$/',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $user->fill($validated);

        // Hapus foto jika user klik tombol ×
        if ($request->input('remove_photo') == '1') {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = null;
        }
        // Upload foto baru jika ada
        elseif ($request->hasFile('photo')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $file = $request->file('photo');
            $filename = uniqid() . '_' . $file->getClientOriginalName();
            $path = 'profile/' . $filename;
            
            // Inisialisasi ImageManager dengan GD Driver
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            
            // Baca dan proses gambar (potong jadi persegi 300x300, lalu kompres ke format jpg)
            $image = $manager->read($file->getRealPath());
            $image->cover(300, 300);
            $encoded = $image->toJpeg(75); // Kualitas 75%
            
            Storage::disk('public')->put($path, (string) $encoded);
            
            $user->profile_picture = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'mentor') {
            if ($user->status !== 'active') {
                $this->updateMentorProfile($request, $user);
            } else {
                $request->validate([
                    'bio' => ['nullable', 'string', 'max:2000'],
                ]);
                $user->profileAccount()->updateOrCreate([], [
                    'bio' => $request->input('bio'),
                ]);
            }
        }

        return Redirect::route('settings.edit')
            ->with('status', 'profile-updated')
            ->with('tab', 'profile');
    }

    /**
     * Simpan / update data tambahan profil mentor (tabel profile_accounts).
     * Relasi: $user->profileAccount()
     */
    protected function updateMentorProfile(Request $request, $user): void
    {
        $data = $request->validate([
            'bio'              => ['nullable', 'string', 'max:2000'],
            'expertise'        => ['nullable', 'string', 'max:255'],
            'linkedin_link'    => ['nullable', 'url', 'max:255'],
            'sinta_link'       => ['nullable', 'url', 'max:255'],
            'scopus_link'      => ['nullable', 'url', 'max:255'],
            'front_title'      => ['nullable', 'string', 'max:50'],
            'back_title'       => ['nullable', 'string', 'max:50'],
            'cv_file'          => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'certificate_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
            'diploma_file'     => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:2048'],
        ]);

        $existing = $user->profileAccount;

        // Mapping field ke folder storage yang sudah ada
        $folderMap = [
            'cv_file'          => 'cv_file',       // storage/app/public/cv_file/
            'certificate_file' => 'certificates',  // storage/app/public/certificates/
            'diploma_file'     => 'diploma_file',  // storage/app/public/diploma_file/
        ];

        // File: hanya timpa kalau ada upload baru, file lama tidak hilang
        foreach ($folderMap as $field => $folder) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($existing && $existing->{$field}) {
                    Storage::disk('public')->delete($existing->{$field});
                }
                $data[$field] = $request->file($field)->store($folder, 'public');
            } else {
                unset($data[$field]);
            }
        }

        $user->profileAccount()->updateOrCreate([], $data);
    }

    /**
     * Update rekening bank mentor (tabel banks).
     * Relasi: $user->banks()
     */
    public function updateBank(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->role === 'mentor', 403);

        $data = $request->validate([
            'bank_name'    => ['required', 'string', 'max:255'],
            'bank_account' => ['required', 'string', 'max:255'],
            'bank_holder'  => ['required', 'string', 'max:255'],
        ]);

        $user->banks()->updateOrCreate([], $data);

        return Redirect::route('settings.edit')
            ->with('status', 'bank-updated')
            ->with('tab', 'bank');
    }

    /**
     * Update password user.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
            'password.min'                      => 'Password minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('settings.edit')
            ->with('status', 'password-updated')
            ->with('tab', 'password');
    }

    /**
     * Hapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Hapus foto profil dari storage/app/public/profile/
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
