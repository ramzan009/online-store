<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\CategoryCreateRequest;
use App\Http\Requests\Admin\Adverts\CategoryUpdateRequest;
use App\Models\Adverts\Category;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()->defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::query()->defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryCreateRequest $request)
    {
        $data = $request->validated();
        $category = Category::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'parent_id' => $data['parent'],
        ]);

        return redirect()->route('adverts.categories.show', $category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $parentsAttributes = $category->parentAttributes();
        $attributes = $category->attributes()->orderBy('sort')->get();
        return view('admin.adverts.categories.show', compact('category', 'attributes', 'parentsAttributes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parents = Category::query()->defaultOrder()->withDepth()->get();
        return view('admin.adverts.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->validated();
        $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'parent_id' => $data['parent'],
        ]);

        return redirect()->route('adverts.categories.show', $category);
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function first(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertAfterNode($first);
        }
        return redirect()->route('admin.adverts.categories.index', $category);
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function up(Category $category)
    {
        $category->up();
        return redirect()->route('admin.adverts.categories.index', $category);
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function down(Category $category)
    {
        $category->down();
        return redirect()->route('admin.adverts.categories.index', $category);
    }

    /**
     * @param Category $category
     * @return RedirectResponse
     */
    public function last(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }
        return redirect()->route('admin.adverts.categories.index', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('adverts.categories.index');
    }
}
