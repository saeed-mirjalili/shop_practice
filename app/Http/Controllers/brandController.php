<?php

namespace App\Http\Controllers;

use App\Http\Resources\brandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class brandController extends mainController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::paginate(2);
        return $this->Response('success', 'get it', [
            'brands' => brandResource::collection($brand),
            'links' => brandResource::collection($brand)->response()->getData()->links,
            'meta' => brandResource::collection($brand)->response()->getData()->meta,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'display_name' => 'required|unique:brands',
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }

        $brand = Brand::create([
            'name' => $request->name,
            'display_name' => $request->display_name
        ]);

        return $this->Response('success', 'okk!', new brandResource($brand),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return $this->Response('success', 'okk!', new brandResource($brand),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'display_name' => 'required|unique:brands',
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }

        $brand->update([
            'name' => $request->name,
            'display_name' => $request->display_name
        ]);

        return $this->Response('success', 'okk!', new brandResource($brand),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        $delete = $brand->delete();
        return $this->Response('success', 'okk!', $delete,200);
    }
}
