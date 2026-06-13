<?php

namespace App\Services\Admin;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories(int $perPage = 10)
    {
        return Category::latest()->paginate($perPage);
    }

    public function createCategory(array $data): Category
    {
        return Category::create([
            'admin_id'             => auth()->id(),
            'category_name'        => $data['category_name'],
            'category_description' => $data['category_description'] ?? null,
            'category_icon'        => $this->prefixIcon($data['category_icon'] ?? 'fa-book'),
            'category_color'       => $data['category_color'] ?? '#7C3AED',
        ]);
    }

    public function updateCategory(Category $category, array $data): bool
    {
        return $category->update([
            'category_name'        => $data['category_name'],
            'category_description' => $data['category_description'] ?? null,
            'category_icon'        => $this->prefixIcon($data['category_icon'] ?? 'fa-book'),
            'category_color'       => $data['category_color'] ?? '#7C3AED',
        ]);
    }

    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }

    private function prefixIcon(string $icon): string
    {
        
        if (!preg_match('/^fa[srb] /', $icon)) {
            return 'fas ' . $icon;
        }

        return $icon;
    }
}
