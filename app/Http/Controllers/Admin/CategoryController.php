<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Aturan validasi untuk create & update category.
     */
    protected function rules(?int $categoryId = null): array
    {
        return [
            'category_name'        => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('categories', 'category_name')->ignore($categoryId)
            ],
            'category_description' => ['nullable', 'string'],
            'category_icon'        => ['nullable', 'string', 'max:100'],
            'category_color'       => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Pesan error custom untuk validasi.
     */
    protected function messages(): array
    {
        return [
            'category_name.required' => 'Nama kategori wajib diisi.',
            'category_name.max'      => 'Nama kategori maksimal 255 karakter.',
            'category_name.unique'   => 'Kategori dengan nama ini sudah ada, mohon gunakan nama lain.',
            'category_icon.max'      => 'Nama icon terlalu panjang.',
            'category_color.max'     => 'Kode warna terlalu panjang.',
        ];
    }

    public function index()
    {
        $categories = Category::latest()->paginate(5);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        Category::create([
            'admin_id'             => Auth::id(),
            'category_name'        => $data['category_name'],
            'category_description' => $data['category_description'] ?? null,
            'category_icon'        => $this->prefixIcon($data['category_icon'] ?? 'fa-book'),
            'category_color'       => $data['category_color'] ?? '#7C3AED',
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate($this->rules($category->id), $this->messages());

        $processedData = [
            'category_name'        => $data['category_name'],
            'category_description' => $data['category_description'] ?? null,
            'category_icon'        => $this->prefixIcon($data['category_icon'] ?? 'fa-book'),
            'category_color'       => $data['category_color'] ?? '#7C3AED',
        ];

        $category->fill($processedData);

        if (! $category->isDirty()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('info', 'Tidak ada perubahan data yang dilakukan pada kategori.');
        }

        $category->save();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Tambahkan prefix fa* jika icon belum punya prefix Font Awesome.
     */
    private function prefixIcon(string $icon): string
    {
        if (!preg_match('/^fa[srb] /', $icon)) {
            return 'fas ' . $icon;
        }

        return $icon;
    }
}