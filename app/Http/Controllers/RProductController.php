<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Product_Image;
use Illuminate\Http\Request;

class RProductController extends Controller
{
    public function index(Request $request)
    {
        // Get query parameters for category name and brand name
        $categoryName = $request->query('category');
        $brandName = $request->query('brand');

        // Query products with eager loading
        $query = Product::with('category', 'sub_category', 'product_image');

        // Apply filters if category name is provided
        if ($categoryName) {
            $query->whereHas('category', function ($query) use ($categoryName) {
                $query->where('name', 'like', '%' . $categoryName . '%');
            });
        }

        // Apply filters if brand name is provided
        if ($brandName) {
            $query->where('brand_name', 'like', '%' . $brandName . '%');
        }

        // Paginate the results
        $product_list = $query->paginate(9);

        // Format the product list
        return productResponse($product_list);
    }


    public function store(ProductRequest $request)
    {

        // Create a new product instance
        $product = new Product();
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->product_name = $request->product_name;
        $product->url = $request->url;
        $product->color = $request->color;
        $product->size = $request->size;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->brand_name = $request->brand_name;
        $product->product_rating_avg = $request->product_rating_avg;
        $product->total_review = $request->total_review;
        $product->interior_condition = $request->interior_condition;
        $product->style = $request->style;
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
                // Generate a unique filename
                $imageName = 'product_images/'. uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('product_images'), $imageName);

                // Save image details to database
                $productImage = new Product_Image();
                $productImage->product_id = $product->id;
                $productImage->image = $imageName;
                $productImage->save();
            }
        }

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }
    public function show(string $id)
    {
        // Fetch the details of the current product
        $product = Product::with('category','sub_category','product_image')->where('id',$id)->first();
        if (empty($product)){
            return response()->json([
                'message' => 'Product Does not exist',
            ],404);
        }
        // Increment the view count
        $product->increment('view_count');

        // Decode the size and color attributes
        $product->size = json_decode($product->size);
        $product->color = json_decode($product->color);

        // Fetch sub-category-wise related products (excluding the current product)
        $subCategoryProducts = Product::with('product_image')
            ->where('sub_category_id', $product->sub_category_id)
            ->where('id', '!=', $product->id)
            ->paginate(4);

        // Fetch brand-wise related products (excluding the current product)
        $brandProducts = Product::with('product_image')
            ->where('brand_name', $product->brand_name)
            ->where('id', '!=', $product->id)
            ->paginate(4);

        return response()->json([
            'message' => 'Product Details',
            'data' => [
                'current_product' => $product,
                'sub_category_products' => $subCategoryProducts,
                'brand_products' => $brandProducts,
            ],
        ]);
    }


    public function update(UpdateProductRequest $request, $id)
    {
        // Find the product by id
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        // Update product data
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->sub_category_id = $request->sub_category_id ?? $product->sub_category_id;
        $product->product_name = $request->product_name ?? $product->product_name;
        $product->url = $request->url ?? $product->url;
        $product->color = $request->color ?? $product->color;
        $product->size = $request->size ?? $product->size;
        $product->regular_price = $request->regular_price ?? $product->regular_price;
        $product->style = $request->style ?? $product->style;
        $product->sale_price = $request->sale_price ?? $product->sale_price;
        $product->brand_name = $request->brand_name ?? $product->brand_name;
        $product->interior_condition = $request->interior_condition ?? $product->interior_condition;
        $product->product_rating_avg = $request->product_rating_avg ?? $product->product_rating_avg;
        $product->total_review = $request->total_review ?? $product->total_review;
        $product->width = $request->width ?? $product->width;
        $product->height = $request->height ?? $product->height;
        $product->strap_drop = $request->strap_drop ?? $product->strap_drop;
        $product->depth = $request->depth ?? $product->depth;
        $product->exterior_condition = $request->exterior_condition ?? $product->exterior_condition;
        $product->material_condition = $request->material_condition ?? $product->material_condition;
        $product->material = $request->material ?? $product->material;
        $product->manufactured_by = $request->manufactured_by ?? $product->manufactured_by;
        $product->interior_color = $request->interior_color ?? $product->interior_color;
        $product->product_code = $request->product_code ?? $product->product_code;
        $product->our_picks = $request->our_picks ?? $product->our_picks;
        $product->details = $request->details ?? $product->details;
        $product->target_audience = $request->target_audience ?? $product->details;
        $product->design_name = $request->design_name ?? $product->details;
        $product->update();

        // Handle product images
        if ($request->hasFile('images')) {
            foreach ($product->product_image as $existingImage) {
                $imagePath = public_path($existingImage->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            // Delete existing images associated with the product
            Product_Image::where('product_id', $product->id)->delete();

            // Upload new images
            foreach ($request->file('images') as $image) {
                $imageName = 'product_images/'. uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('product_images'), $imageName);

                // Save image details to database
                $productImage = new Product_Image();
                $productImage->product_id = $product->id;
                $productImage->image = $imageName;
                $productImage->save();
            }
        }
        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    public function destroy(string $id)
    {
        // Find the product by id
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Delete associated images from the public folder
        foreach ($product->product_image as $existingImage) {
            $imagePath = public_path($existingImage->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete associated images from the database
        Product_Image::where('product_id', $product->id)->delete();

        // Delete the product
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

}
