<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarosalRequest;
use App\Models\Carosal;
use Illuminate\Http\Request;

class CarosalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $show_carosal = Carosal::orderBy('id', 'desc')->get();

        $carosals = [];

        foreach ($show_carosal as $carosal) {
            $carosal['image'] = json_decode($carosal['image']);
            $carosals[] = $carosal;
        }

        if ($show_carosal) {
            return response()->json([
                'status' => 'success',
                'data' => $carosals
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => []
            ], 200);
        }
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
    public function store(CarosalRequest $request)
    {
        $imagePaths = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName = 'product_images/' . $image->getClientOriginalName();
                $image->move(public_path('product_images'), $imageName);
                $imagePaths[] = $imageName;  // Fix here
            }
        }
        $add_carosal = new Carosal();
        $add_carosal->title = $request->title;
        $add_carosal->sub_title = $request->sub_title;
        $add_carosal->description = $request->description;
        $add_carosal->brand_name = $request->brand_name;
        $add_carosal->url = $request->url;
        $add_carosal->image = json_encode($imagePaths);  // Assigning the array of image paths
        $add_carosal->save();
        if ($add_carosal) {
            return response()->json([
                'status' => 'success',
                'message' => 'Insert carosal successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $show_carosal = Carosal::where('id', $id)->first();

        if ($show_carosal) {
            $decoded_image = json_decode($show_carosal['image'], true);

            if ($decoded_image !== null) {
                $show_carosal['image'] = $decoded_image;  // Update the image field with decoded image data
            }

            return response()->json([
                'status' => 'success',
                'data' => $show_carosal
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => []
            ], 200);
        }
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
    public function update(Request $request)
    {
        $update_carosal = Carosal::find($request->id);

        // Handle image update only if new images are provided
        if ($request->hasFile('image')) {
            // Delete existing image file
            $existingImages = json_decode($update_carosal->image, true);
            foreach ($existingImages as $existingImage) {
                if (file_exists(public_path($existingImage))) {
                    unlink(public_path($existingImage));
                }
            }

            // Upload new image(s)
            $imagePaths = [];
            foreach ($request->file('image') as $image) {
                $imageName = 'product_images/' . $image->getClientOriginalName();
                $image->move(public_path('product_images'), $imageName);
                $imagePaths[] = $imageName;
            }
            $update_carosal->image = json_encode($imagePaths);
        } elseif ($request->filled('image')) {
            // If image field is not empty but no new image is provided, retain previous image
            $update_carosal->image = $request->image;
        }

        // Update other fields
        $update_carosal->title = $request->title ?? $update_carosal->title;
        $update_carosal->sub_title = $request->sub_title ?? $update_carosal->sub_title;
        $update_carosal->description = $request->description ?? $update_carosal->description;
        $update_carosal->brand_name = $request->brand_name ?? $update_carosal->brand_name;
        $update_carosal->url = $request->url ?? $update_carosal->url;

        $update_carosal->save();

        if ($update_carosal) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update carosal successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the record
        $carosal = Carosal::find($id);

        // Check if the record exists
        if ($carosal) {
            // Check if the image exists
            if ($carosal->image) {
                $imagePaths = json_decode($carosal->image, true);

                // Iterate through each image path
                foreach ($imagePaths as $imagePath) {
                    // Check if $imagePath is a valid path
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            // Delete the record
            $delete_carosal = $carosal->delete();

            if ($delete_carosal) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Record deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete record'
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found'
            ], 404);
        }
    }
}
