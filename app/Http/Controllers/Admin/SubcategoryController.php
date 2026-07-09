<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function create(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $selectedCategoryId = $request->query('category_id');
        return view('admin.subcategories.create', compact('categories', 'selectedCategoryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                }),
            ],
            'default_test_size' => 'nullable|integer|min:1|max:100',
            'default_test_time' => 'nullable|integer|min:1|max:300',
        ]);

        Subcategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Subcategory created successfully!');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                })->ignore($subcategory->id),
            ],
            'default_test_size' => 'nullable|integer|min:1|max:100',
            'default_test_time' => 'nullable|integer|min:1|max:300',
        ]);

        $subcategory->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Subcategory updated successfully!');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Subcategory deleted successfully!');
    }
}
