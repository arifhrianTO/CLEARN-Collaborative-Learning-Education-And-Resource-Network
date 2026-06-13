<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StudentRegisterRequest;
use App\Services\Auth\RegisterStudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentRegisterController extends Controller
{
    public function __construct(private readonly RegisterStudentService $registerStudentService)
    {
    }

    public function showForm(): View
    {
        return view('auth.register-student');
    }

    public function register(StudentRegisterRequest $request): RedirectResponse
    {
        $this->registerStudentService->handle($request->validated());

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
