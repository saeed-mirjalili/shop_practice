<?php

namespace App\Http\Controllers;

use App\Http\Resources\productResource;
use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends mainController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::paginate(2);
        return $this->Response('success', 'get it', [
            'products' => productResource::collection($product->load('productImage')),
            'links' => productResource::collection($product)->response()->getData()->links,
            'meta' => productResource::collection($product)->response()->getData()->meta,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required',
            'primary_image' => 'required|image',
            'price' => 'integer',
            'quantity' => 'integer',
            'description' => 'required',
            'delivery_amount' => 'integer',
            'images.*' => 'nullable|image'
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }
        /**
         * Taking the original photo.
         */
        $primaryImageName = Carbon::now()->microsecond.'.'.$request->primary_image->extension();
        $request->primary_image->storeAs('images/products',$primaryImageName,'public');

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'primary_image' => $primaryImageName,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'delivery_amount' => $request->delivery_amount,
        ]);

        /**
         * Taking an array of photos.
         */
        if ($request->has('images')) {
            $filenameimages = [];
            foreach ($request->images as $image) {
                $filenameimage = Carbon::now()->microsecond.'.'.$image->extension();
                $image->storeAs('images/products',$filenameimage,'public');
                array_push($filenameimages, $filenameimage);
            }
        }

        if ($request->has('images')) {
            foreach ($filenameimages as $filenameimage) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $filenameimage
                ]);
            }
        }

        return $this->Response('success', 'okk!', new productResource($product),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->Response('success', 'okk!', new productResource($product->load('productImage')),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'string',
            'category_id' => 'integer',
            'primary_image' => 'image',
            'price' => 'integer',
            'quantity' => 'integer',
            'description' => 'string',
            'delivery_amount' => 'integer',
            'images.*' => 'nullable|image'
        ]);

        if ($validate->fails()) {
            return $this->Response('Error', $validate->messages(), null,500);
        }
        /**
         * Taking the original photo.
         */
        if ($request->has('primary_image')) {
            $primaryImageName = Carbon::now()->microsecond.'.'.$request->primary_image->extension();
            $request->primary_image->storeAs('images/products',$primaryImageName,'public');
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'primary_image' => $request->has('primary_image') ? $primaryImageName : $product->primary_image,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'delivery_amount' => $request->delivery_amount,
        ]);

        /**
         * Taking an array of photos.
         */
        if ($request->has('images')) {
            $filenameimages = [];
            foreach ($request->images as $image) {
                $filenameimage = Carbon::now()->microsecond.'.'.$image->extension();
                $image->storeAs('images/products',$filenameimage,'public');
                array_push($filenameimages, $filenameimage);
            }
        }

        if ($request->has('images')) {
            foreach ($product->productImage as $image) {
                $image->delete();
            }
            foreach ($filenameimages as $filenameimage) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $filenameimage
                ]);
            }
        }

        return $this->Response('success', 'okk!', new productResource($product),200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $delete = $product->delete();
        return $this->Response('success', 'okk!', $delete,200);
    }
}
