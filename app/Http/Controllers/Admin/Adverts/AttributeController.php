<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\Attributes\AttributeCreateRequest;
use App\Http\Requests\Admin\Adverts\Attributes\AttributeUpdateRequest;
use App\Models\Adverts\Attribute;
use App\Models\Adverts\Category;
use Illuminate\Http\Request;

class AttributeController extends Controller
{

    public function create(Category $category)
    {
        $types = Attribute::typesList();

        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }

    public function store(AttributeCreateRequest $request, Category $category)
    {
        $data = $request->validated();

        $attribute = $category->attributes()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'required' => (bool)$data['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $data['variants'])),
            'sort' => $data['sort'],
        ]);
        return redirect()->route('admin.adverts.categories.attributes.show', [$category, $attribute]);
    }

    public function show(Category $category, Attribute $attribute)
    {
        return view('admin.adverts.categories.attributes.show', compact('category', 'attribute'));
    }


    public function edit(Category $category, Attribute $attribute)
    {
        $types = Attribute::typesList();

        return view('admin.adverts.categories.attributes.edit', compact('category', 'attribute', 'types'));
    }

    public function update(AttributeUpdateRequest $request, Category $category, Attribute $attribute)
    {
        $data = $request->validated();

        $category->attributes()->findOrFail($attribute->id)->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'required' => (bool)$data['required'],
            'variants' => array_map('trim', preg_split('#[\r\n]+#', $data['variants'])),
            'sort' => $data['sort'],
        ]);
        return redirect()->route('admin.adverts.categories.show', $category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.adverts.categories.show', $category);
    }

}
