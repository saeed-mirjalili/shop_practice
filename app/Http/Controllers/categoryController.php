<?php

namespace App\Http\Controllers;

use App\Http\Resources\categoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categoryController extends mainController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::paginate(2);
        return $this->Response('success', 'get it', [
            'categories' => categoryResource::collection($category),
            'links' => categoryResource::collection($category)->response()->getData()->links,
            'meta' => categoryResource::collection($category)->response()->getData()->meta,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'brand_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }

        $category = Category::create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return $this->Response('success', 'okk!', new categoryResource($category),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->Response('success', 'okk!', new categoryResource($category),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validate = Validator::make($request->all(), [
            'brand_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }

        $category->update([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'description' => $request->description
        ]);

        return $this->Response('success', 'okk!', new categoryResource($category),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $delete = $category->delete();
        return $this->Response('success', 'okk!', $delete,200);
    }

    public function parent(Category $category)
    {
        return $this->Response('success', 'okk!', new categoryResource($category->load('brand')),200);
    }

    public function children(Category $category)
    {
        return $this->Response('success', 'okk!', new categoryResource($category->load('product')),200);
    }
}
