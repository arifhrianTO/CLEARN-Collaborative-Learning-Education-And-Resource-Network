<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\FinalProject;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FinalProjectController extends Controller
{
    /**
     * Menampilkan form tambah final project.
     */
    public function create(Session $session)
    {
        $session->load([
            'course.sessions.finalProjects',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan course milik mentor yang sedang login
        |--------------------------------------------------------------------------
        */
        if (
            !$session->course ||
            (int) $session->course->mentor_id !== (int) auth()->id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | Final Project hanya boleh berada di session terakhir
        |--------------------------------------------------------------------------
        */
        $lastSessionId = $session->course
            ->sessions()
            ->orderByDesc('id')
            ->value('id');

        if ((int) $session->id !== (int) $lastSessionId) {
            return redirect()
                ->route(
                    'mentor.courses.sessions.edit',
                    $session->course_id
                )
                ->with(
                    'error',
                    'Final Project hanya dapat ditambahkan pada session terakhir.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Satu course hanya boleh memiliki satu Final Project
        |--------------------------------------------------------------------------
        */
        $courseHasFinalProject = FinalProject::whereHas(
            'session',
            function ($query) use ($session) {
                $query->where('course_id', $session->course_id);
            }
        )->exists();

        if ($courseHasFinalProject) {
            return redirect()
                ->route(
                    'mentor.courses.sessions.edit',
                    $session->course_id
                )
                ->with(
                    'error',
                    'Course ini sudah memiliki Final Project.'
                );
        }

        return view(
            'mentor.final-projects.create',
            compact('session')
        );
    }

    /**
     * Menyimpan Final Project.
     */
    public function store(Request $request, Session $session)
    {
        $session->load([
            'course.sessions.finalProjects',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi kepemilikan course
        |--------------------------------------------------------------------------
        */
        if (
            !$session->course ||
            (int) $session->course->mentor_id !== (int) auth()->id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan session adalah session terakhir
        |--------------------------------------------------------------------------
        */
        $lastSessionId = $session->course
            ->sessions()
            ->orderByDesc('id')
            ->value('id');

        if ((int) $session->id !== (int) $lastSessionId) {
            return redirect()
                ->route(
                    'mentor.courses.sessions.edit',
                    $session->course_id
                )
                ->with(
                    'error',
                    'Final Project hanya dapat ditambahkan pada session terakhir.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan course belum memiliki Final Project
        |--------------------------------------------------------------------------
        */
        $courseHasFinalProject = FinalProject::whereHas(
            'session',
            function ($query) use ($session) {
                $query->where('course_id', $session->course_id);
            }
        )->exists();

        if ($courseHasFinalProject) {
            return redirect()
                ->route(
                    'mentor.courses.sessions.edit',
                    $session->course_id
                )
                ->with(
                    'error',
                    'Course ini sudah memiliki Final Project.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Aturan validasi
        |--------------------------------------------------------------------------
        */
        $rules = [
            'project_title' => [
                'required',
                'string',
                'max:255',
            ],

            'project_description' => [
                'required',
                'string',
            ],

            'duration_days' => [
                'required',
                'integer',
                'min:1',
                'max:365',
            ],

            'allowed_extensions' => [
                'required',
                'string',
                Rule::in(['.zip,.rar', '.pdf', '.zip,.rar,.pdf']),
            ],

            'material_type' => [
                'nullable',
                'in:pdf,link',
            ],

            'material_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,zip,rar',
                'max:10240',
            ],

            'material_url' => [
                'nullable',
                'url',
                'max:1000',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Material wajib sesuai jenis yang dipilih
        |--------------------------------------------------------------------------
        */
        if ($request->material_type === 'pdf') {
            $rules['material_file'] = [
                'required',
                'file',
                'mimes:pdf,doc,docx,zip,rar',
                'max:10240',
            ];
        }

        if ($request->material_type === 'link') {
            $rules['material_url'] = [
                'required',
                'url',
                'max:1000',
            ];
        }

        $validated = $request->validate(
            $rules,
            [
                'project_title.required' =>
                'Judul Final Project wajib diisi.',

                'project_description.required' =>
                'Panduan atau deskripsi Final Project wajib diisi.',

                'duration_days.required' =>
                'Durasi pengerjaan wajib diisi.',

                'duration_days.integer' =>
                'Durasi pengerjaan harus berupa angka.',

                'duration_days.min' =>
                'Durasi pengerjaan minimal 1 hari.',

                'duration_days.max' =>
                'Durasi pengerjaan maksimal 365 hari.',

                'allowed_extensions.required' =>
                'Format file yang diizinkan wajib dipilih.',

                'allowed_extensions.in' =>
                'Format file yang dipilih tidak valid.',

                'material_file.required' =>
                'File panduan wajib dipilih.',

                'material_file.mimes' =>
                'File panduan harus berupa PDF, DOC, DOCX, ZIP, atau RAR.',

                'material_file.max' =>
                'Ukuran file panduan maksimal 10 MB.',

                'material_url.required' =>
                'Link panduan wajib diisi.',

                'material_url.url' =>
                'Link panduan tidak valid.',
            ]
        );

        DB::transaction(function () use (
            $request,
            $validated,
            $session
        ) {
            /*
            |--------------------------------------------------------------------------
            | Simpan Final Project
            |--------------------------------------------------------------------------
            */
            $finalProject = FinalProject::create([
                'sessions_id'         => $session->id,
                'project_title'       => $validated['project_title'],
                'project_description' => $validated['project_description'],
                'duration_days'       => $validated['duration_days'],
                'allowed_extensions'  => $validated['allowed_extensions'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan material tambahan jika ada
            |--------------------------------------------------------------------------
            */
            if ($request->material_type === 'pdf') {
                $filePath = $request
                    ->file('material_file')
                    ->store(
                        'final-project-materials',
                        'public'
                    );

                $finalProject->materials()->create([
                    'type'      => 'pdf',
                    'file_path' => $filePath,
                    'url'       => null,
                ]);
            }

            if ($request->material_type === 'link') {
                $finalProject->materials()->create([
                    'type'      => 'link',
                    'file_path' => null,
                    'url'       => $validated['material_url'],
                ]);
            }
        });

        return redirect()
            ->route(
                'mentor.courses.sessions.edit',
                $session->course_id
            )
            ->with(
                'success',
                'Final Project berhasil ditambahkan.'
            );
    }

    /**
     * Menghapus Final Project.
     */
    public function destroy(FinalProject $finalProject)
    {
        $finalProject->load([
            'session.course',
            'materials',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan Final Project milik mentor yang login
        |--------------------------------------------------------------------------
        */
        if (
            !$finalProject->session ||
            !$finalProject->session->course ||
            (int) $finalProject->session->course->mentor_id !==
            (int) auth()->id()
        ) {
            abort(403, 'Anda tidak memiliki akses ke Final Project ini.');
        }

        $courseId = $finalProject->session->course_id;

        DB::transaction(function () use ($finalProject) {
            /*
            |--------------------------------------------------------------------------
            | Hapus file material dari storage
            |--------------------------------------------------------------------------
            */
            foreach ($finalProject->materials as $material) {
                if (
                    $material->file_path &&
                    Storage::disk('public')->exists(
                        $material->file_path
                    )
                ) {
                    Storage::disk('public')->delete(
                        $material->file_path
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Hapus material dan Final Project
            |--------------------------------------------------------------------------
            */
            $finalProject->materials()->delete();
            $finalProject->delete();
        });

        return redirect()
            ->route(
                'mentor.courses.sessions.edit',
                $courseId
            )
            ->with(
                'success',
                'Final Project berhasil dihapus.'
            );
    }
}
