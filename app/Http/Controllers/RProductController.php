<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        return $request;
        // Validate the incoming request data
        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'product_name' => 'required',
            'url' => 'required',
            'regular_price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'color' => 'nullable|string|max:255',
            'brand_name' => 'nullable|string',
            'product_rating_avg' => 'nullable|string',
            'total_review' => 'nullable|integer',
            'size' => 'nullable',
//            'style' => 'nullable|string',
            'width' => 'nullable',
            'depth' => 'nullable|string',
            'exterior_condition' => 'nullable|string',
            'material_condition' => 'nullable|string',
            'material' => 'nullable|string',
            'manufactured_by' => 'nullable|string',
            'details' => 'nullable|string',
            'design_name' => 'nullable|string',
            'height' => 'nullable|string',
            'strap_drop' => 'nullable|string',
            'interior_condition' => 'nullable|string',
            'product_code' => 'nullable|integer',
            'interior_color' => 'nullable|string',
            'our_picks' => 'nullable|boolean',
            'target_audience' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rule for multiple images
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new product instance
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->product_name = $request->product_name;
        $product->url = $request->url;
        $product->color = $request->color ?? null;
        $product->size = $request->size;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->brand_name = $request->brand_name;
        $product->product_rating_avg = $request->product_rating_avg;
        $product->total_review = $request->total_review;
//        $product->style = $request->style;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->strap_drop = $request->strap_drop;
        $product->depth = $request->depth;
        $product->exterior_condition = $request->exterior_condition;
        $product->material_condition = $request->material_condition;
        $product->material = $request->material;
        $product->manufactured_by = $request->manufactured_by;
        $product->interior_color = $request->interior_color;
        $product->product_code = $request->product_code;
        $product->our_picks = $request->our_picks;
        $product->details = $request->details;
        $product->target_audience = $request->target_audience;
        $product->design_name = $request->design_name;
        $product->save();

        // Handle product images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $image->getClientOriginalName();
                $image->move(public_path('product_images'), $imageName);

                // Save image details to database
                $productImage = new Product_Image();
                $productImage->product_id = $product->id;
                $productImage->product_image = $imageName;
                $productImage->save();
            }
        }

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
