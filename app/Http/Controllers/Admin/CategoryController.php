<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80', 'unique:categories,name'],
            'icon' => ['nullable', 'string', 'max:10'],
            'requires_qualification' => ['nullable', 'boolean'],
        ]);

        Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'icon' => $data['icon'] ?: '🐝',
            'requires_qualification' => (bool) ($data['requires_qualification'] ?? false),
        ]);

        return back()->with('status', 'Categoria criada!');
    }

    public function toggle(Category $category, string $field)
    {
        abort_unless(in_array($field, ['active', 'requires_qualification']), 404);
        $category->update([$field => !$category->$field]);
        return back();
    }
}
