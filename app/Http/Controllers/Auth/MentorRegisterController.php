<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\MentorRegisterRequest;
use App\Services\Auth\RegisterMentorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MentorRegisterController extends Controller
{
    public function __construct(private readonly RegisterMentorService $registerMentorService)
    {
    }

    public function showForm(): View
    {
        return view('auth.register-mentor');
    }

    public function register(MentorRegisterRequest $request): RedirectResponse
    {
        $this->registerMentorService->handle($request->validated());

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
