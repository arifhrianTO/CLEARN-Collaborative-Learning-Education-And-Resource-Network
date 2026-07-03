<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Pastikan course milik user mentor yang sedang login.
     */
    private function authorizeCourse(Course $course): void
    {
        if ((int) $course->mentor_id !== (int) Auth::id()) {
            abort(403, 'Kamu tidak memiliki akses ke course ini.');
        }
    }

    /**
     * Menampilkan semua course milik mentor.
     */
    public function index()
    {
        $courseQuery = Course::query()
            ->where('mentor_id',  Auth::id());

        $totalCourse = (clone $courseQuery)->count();

        $totalApproved = (clone $courseQuery)
            ->where('status_review', 'approved')
            ->count();

        $totalPending = (clone $courseQuery)
            ->where('status_review', 'pending')
            ->count();

        $courses = (clone $courseQuery)
            ->with([
                'category',
                'sessions',
            ])
            ->withAvg('rates', 'course_rate')
            ->withCount('enrollments')
            ->latest()
            ->paginate(3)
            ->withQueryString();

        return view('mentor.courses.index', compact(
            'courses',
            'totalCourse',
            'totalApproved',
            'totalPending'
        ));
    }

    /**
     * Halaman tambah course.
     */
    public function create()
    {
        if (Auth::user()->status === 'pending') {
            return redirect()->route('mentor.courses.index')->with('error', 'Status akun Anda masih pending. Anda belum bisa menambahkan kursus.');
        } elseif (Auth::user()->status === 'rejected') {
            return redirect()->route('mentor.courses.index')->with('error', 'Akun Anda ditolak. Silakan perbaiki data di menu Pengaturan.');
        }

        // Cek apakah data rekening bank sudah diisi
        $bank = Auth::user()->banks;
        if (!$bank || empty($bank->bank_name) || empty($bank->bank_account) || empty($bank->bank_holder)) {
            return redirect()->route('mentor.courses.index')->with('showBankPopup', true);
        }
        
        $categories = Category::latest()->get();

        return view('mentor.courses.create', compact('categories'));
    }

    /**
     * Menyimpan course baru.
     */
    public function store(Request $request)
    {
        if (Auth::user()->status === 'pending') {
            return redirect()->route('mentor.courses.index')->with('error', 'Status akun Anda masih pending. Anda belum bisa menambahkan kursus.');
        } elseif (Auth::user()->status === 'rejected') {
            return redirect()->route('mentor.courses.index')->with('error', 'Akun Anda ditolak. Silakan perbaiki data di menu Pengaturan.');
        }

        // Cek kembali saat store (jaga-jaga user memaksa masuk via URL API/Form Bypass)
        $bank = Auth::user()->banks;
        if (!$bank || empty($bank->bank_name) || empty($bank->bank_account) || empty($bank->bank_holder)) {
            return redirect()->route('mentor.courses.index')->with('showBankPopup', true);
        }
        
        $validated = $request->validate([
            'course_title' => [
                'required',
                'string',
                'max:255',
            ],
            'course_description' => [
                'required',
                'string',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'course_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'course_thumbnail' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'session_count' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],
        ], [
            'course_title.required' => 'Judul course wajib diisi.',
            'course_description.required' => 'Deskripsi course wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'course_price.required' => 'Harga kursus wajib diisi.',
            'course_price.numeric' => 'Harga course harus berupa angka.',
            'course_thumbnail.required' => 'Thumbnail course wajib dipilih.',
            'course_thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'course_thumbnail.max' => 'Ukuran thumbnail maksimal 2 MB.',
            'session_count.required' => 'Jumlah pertemuan wajib diisi.',
            'session_count.min' => 'Jumlah pertemuan minimal 1.',
            'session_count.max' => 'Jumlah pertemuan maksimal 50.',
        ]);

        $thumbnailPath = null;

        try {
            DB::beginTransaction();

            $thumbnailPath = $request
                ->file('course_thumbnail')
                ->store('course-thumbnails', 'public');

            $course = Course::create([
                'mentor_id' =>  Auth::id(),
                'category_id' => $validated['category_id'],
                'course_title' => $validated['course_title'],
                'course_slug' => Str::slug($validated['course_title']) . '-' . time(),
                'course_description' => $validated['course_description'],
                'course_thumbnail' => $thumbnailPath,
                'course_price' => $validated['course_price'],

                // Course baru belum diajukan ke admin.
                'status_publish' => 'draft',
                'status_review' => 'draft',
                'rejection_reason' => null,
            ]);

            for ($i = 1; $i <= $validated['session_count']; $i++) {
                Session::create([
                    'course_id' => $course->id,
                    'sessions_title' => 'Pertemuan ' . $i,
                    'sessions_description' => null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('mentor.courses.show', $course->id)
                ->with(
                    'success',
                    'Kursus berhasil dibuat. Pertemuan otomatis sudah dibuat.'
                );
        } catch (\Throwable $error) {
            DB::rollBack();

            if (
                $thumbnailPath &&
                Storage::disk('public')->exists($thumbnailPath)
            ) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            report($error);

            return back()
                ->withInput()
                ->with('error', 'Kursus gagal dibuat. Silakan coba kembali.');
        }
    }

    /**
     * Menampilkan detail course.
     */
    public function show(Course $course)
    {
        $this->authorizeCourse($course);

        $course->load([
            'category',
            'sessions.lessons.materials',
            'sessions.exercises.questions.options',
            'sessions.finalProjects',
        ]);

        return view('mentor.courses.show', compact('course'));
    }

    /**
     * Halaman edit course.
     */
    public function edit(Course $course)
    {
        $this->authorizeCourse($course);

        if ($course->status_review === 'approved') {
            return redirect()
                ->route('mentor.courses.show', $course->id)
                ->with(
                    'error',
                    'Course yang sudah disetujui admin tidak dapat diedit.'
                );
        }

        $categories = Category::latest()->get();

        $course->load('sessions');

        return view('mentor.courses.edit', compact(
            'course',
            'categories'
        ));
    }

    /**
     * Memperbarui course.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        if ($course->status_review === 'approved') {
            return back()->with(
                'error',
                'Course yang sudah disetujui admin tidak dapat diperbarui.'
            );
        }

        $currentSessionCount = $course->sessions()->count();

        $validated = $request->validate([
            'course_title' => [
                'required',
                'string',
                'max:255',
            ],
            'course_description' => [
                'required',
                'string',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'course_price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'course_thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'session_count' => [
                'required',
                'integer',
                'min:' . $currentSessionCount,
                'max:50',
            ],
        ], [
            'session_count.min' =>
            'Jumlah pertemuan tidak boleh lebih kecil dari pertemuan yang sudah ada.',
            'session_count.max' =>
            'Jumlah pertemuan maksimal 50.',
        ]);

        $oldThumbnail = $course->course_thumbnail;
        $newThumbnail = null;

        try {
            DB::beginTransaction();

            if ($request->hasFile('course_thumbnail')) {
                $newThumbnail = $request
                    ->file('course_thumbnail')
                    ->store('course-thumbnails', 'public');
            }

            $course->update([
                'category_id' => $validated['category_id'],
                'course_title' => $validated['course_title'],
                'course_slug' => Str::slug($validated['course_title']) . '-' . $course->id,
                'course_description' => $validated['course_description'],
                'course_price' => $validated['course_price'],
                'course_thumbnail' => $newThumbnail ?: $oldThumbnail,

                // Setelah diedit, course harus diajukan ulang.
                'status_review' => 'draft',
                'status_publish' => 'draft',
                'rejection_reason' => null,
            ]);

            if ($validated['session_count'] > $currentSessionCount) {
                for (
                    $i = $currentSessionCount + 1;
                    $i <= $validated['session_count'];
                    $i++
                ) {
                    Session::create([
                        'course_id' => $course->id,
                        'sessions_title' => 'Pertemuan ' . $i,
                        'sessions_description' => null,
                    ]);
                }
            }

            DB::commit();

            if (
                $newThumbnail &&
                $oldThumbnail &&
                Storage::disk('public')->exists($oldThumbnail)
            ) {
                Storage::disk('public')->delete($oldThumbnail);
            }

            return redirect()
                ->route('mentor.courses.show', $course->id)
                ->with(
                    'success',
                    'Kursus berhasil diperbarui dan kembali menjadi draft.'
                );
        } catch (\Throwable $error) {
            DB::rollBack();

            if (
                $newThumbnail &&
                Storage::disk('public')->exists($newThumbnail)
            ) {
                Storage::disk('public')->delete($newThumbnail);
            }

            report($error);

            return back()
                ->withInput()
                ->with('error', 'Kursus gagal diperbarui.');
        }
    }

    /**
     * Menghapus course.
     *
     * Course approved/publish tidak boleh dihapus mentor.
     */
    public function destroy(Course $course)
    {
        $this->authorizeCourse($course);

        if (
            $course->status_review === 'approved' ||
            $course->status_publish === 'publish'
        ) {
            return back()->with(
                'error',
                'Kursus yang sudah diverifikasi dan dipublikasikan tidak dapat dihapus.'
            );
        }

        $thumbnailPath = $course->course_thumbnail;

        try {
            DB::beginTransaction();

            $course->delete();

            DB::commit();

            if (
                $thumbnailPath &&
                Storage::disk('public')->exists($thumbnailPath)
            ) {
                Storage::disk('public')->delete($thumbnailPath);
            }

            return redirect()
                ->route('mentor.courses.index')
                ->with('success', 'Kursus berhasil dihapus.');
        } catch (\Throwable $error) {
            DB::rollBack();

            report($error);

            return back()->with(
                'error',
                'Kursus gagal dihapus. Periksa foreign key dan cascade delete.'
            );
        }
    }

    /**
     * Mengajukan course kepada admin.
     */
    public function submit(Course $course)
    {
        $this->authorizeCourse($course);

        if ($course->status_review === 'pending') {
            return back()->with(
                'error',
                'Kursus sedang menunggu verifikasi admin.'
            );
        }

        if (
            $course->status_review === 'approved' ||
            $course->status_publish === 'publish'
        ) {
            return back()->with(
                'error',
                'Kursus sudah disetujui dan dipublikasikan.'
            );
        }

        $course->load([
            'sessions.lessons.materials',
            'sessions.exercises.questions.options',
            'sessions.finalProjects',
        ]);

        if ($course->sessions->isEmpty()) {
            return back()->with(
                'error',
                'Kursus belum memiliki pertemuan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi final project
        |--------------------------------------------------------------------------
        */

        $lastSession = $course->sessions->last();

        if (!$lastSession) {
            return back()->with(
                'error',
                'Pertemuan terakhir tidak ditemukan.'
            );
        }

        if ($lastSession->finalProjects->isEmpty()) {
            return back()->with(
                'error',
                'Pertemuan terakhir wajib memiliki Tugas Akhir.'
            );
        }

        foreach ($course->sessions as $session) {
            if (
                $session->id !== $lastSession->id &&
                $session->finalProjects->isNotEmpty()
            ) {
                return back()->with(
                    'error',
                    'Tugas Akhir hanya boleh berada pada pertemuan terakhir.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi lesson dan materi (Hanya untuk sesi BUKAN tugas akhir)
        |--------------------------------------------------------------------------
        */

        foreach ($course->sessions as $index => $session) {
            $sessionNumber = $index + 1;

            // Lewati validasi lesson untuk session terakhir (Tugas Akhir)
            if ($session->id === $lastSession->id) {
                continue;
            }

            if ($session->lessons->isEmpty()) {
                return back()->with(
                    'error',
                    "Pertemuan {$sessionNumber} belum memiliki Pelajaran."
                );
            }

            foreach ($session->lessons as $lesson) {
                if ($lesson->materials->isEmpty()) {
                    return back()->with(
                        'error',
                        "Pelajaran \"{$lesson->lessons_title}\" pada Pertemuan {$sessionNumber} belum memiliki materi."
                    );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ajukan course ke admin
        |--------------------------------------------------------------------------
        */

        $course->update([
            'status_review' => 'pending',
            'status_publish' => 'draft',
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('mentor.courses.show', $course->id)
            ->with(
                'success',
                'Kursus berhasil diajukan dan sedang menunggu verifikasi admin.'
            );
    }
}
