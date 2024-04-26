<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TerandingBrandRequest;
use App\Models\TerendingBrend;
use Illuminate\Http\Request;

class TerandingBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $show_tranding_brand = TerendingBrend::orderBy('id', 'desc')->paginate(10);

        if ($show_tranding_brand) {
            return response()->json([
                'status' => 'success',
                'data' => $show_tranding_brand
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
    public function store(TerandingBrandRequest $request)
    {
        $teranding_brand = new TerendingBrend();
        $teranding_brand->name = $request->brand_name;

        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('product_images', $filename);

            // Concatenate directory path with filename to create full image path
            $imagePath = 'product_images/' . $filename;

            // Add image path to the $add_slide object
            $teranding_brand->logo = $imagePath;
        }

        $teranding_brand->save();
        if ($teranding_brand) {
            return response()->json([
                'status' => 'success',
                'data' => $teranding_brand
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
        $singel_brand = TerendingBrend::where('id', $id)->first();

        if ($singel_brand) {
            return response()->json([
                'status' => 'success',
                'data' => $singel_brand
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
    public function update(Request $request, string $id)
    {
        $update_brand = TerendingBrend::find($id);

        // Update category_id
        $update_brand->name = $request->brand_name ?? $update_brand->brand_name;

        // Check if a new image has been uploaded
        if ($request->hasFile('logo')) {
            // Get the file from the request
            $file = $request->file('logo');

            // Get the extension of the file
            $extension = $file->getClientOriginalExtension();

            // Generate a unique filename
            $filename = time() . '.' . $extension;

            // Move the uploaded file to the desired directory
            $file->move('product_images', $filename);

            // Concatenate directory path with filename to create full image path
            $imagePath = 'product_images/' . $filename;

            // Unlink the existing image if it exists
            if ($update_brand->logo && file_exists(public_path($update_brand->logo))) {
                unlink(public_path($update_brand->logo));
            }

            // Set the new image path
            $update_brand->logo = $imagePath;
        } elseif ($request->has('preview_image')) {
            // If no new image is uploaded but a preview image is provided
            $update_brand->logo = $request->preview_image;
        }

        // Save the updated slide
        $update_brand->save();

        // Check if the slide was successfully updated
        if ($update_brand) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tranding barand updated successfully'
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
        $Brand = TerendingBrend::find($id);

        // Check if the record exists
        if ($Brand) {
            // Check if the image exists
            if ($Brand->logo) {
                $imagePath = $Brand->logo;

                // Check if $imagePath is a valid path
                if (file_exists(public_path($imagePath))) {
                    // Delete the image file
                    unlink(public_path($imagePath));
                }
            }

            // Delete the record
            $delete_brand = $Brand->delete();

            if ($delete_brand) {
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
