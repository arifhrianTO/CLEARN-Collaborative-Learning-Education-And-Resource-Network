<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentRegisterController extends Controller
{
    /**
     * Aturan validasi untuk registrasi student.
     */
    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email', new \App\Rules\DisposableEmail],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'occupation' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function showForm(): View
    {
        return view('auth.register-student');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'role'       => 'student',
                'status'     => 'active',
                'occupation' => $data['occupation'] ?? null,
            ]);

            event(new Registered($user));
        });

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
