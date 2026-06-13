<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MentorRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
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
}
