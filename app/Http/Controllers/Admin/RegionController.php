<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Regions\RegionCreateRequest;
use App\Http\Requests\Admin\Regions\RegionUpdateRequest;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::query()->where('parent_id', null)->orderBy('name')->get();

        return view('admin.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parent = null;
        if ($request->get('parent')) {
            $parent = Region::query()->findOrFail($request->get('parent'));
        }

        return view('admin.regions.create', compact('parent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegionCreateRequest $request)
    {
       $data = $request->validated();

        $region = Region::query()->create([
            'name'     => $data['name'],
            'slug'    => $data['slug'],
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.regions.show', $region);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        $regions = Region::query()->where('parent_id', $region->id)->orderBy('name')->get();
        return view('admin.regions.show', compact('regions', 'region'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegionUpdateRequest $request, Region $region)
    {
        $data = $request->validated();

        $region->update([
            'name'     => $data['name'],
            'slug'    => $data['slug'],
        ]);
        return redirect()->route('admin.regions.show', $region);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index');
    }

}
