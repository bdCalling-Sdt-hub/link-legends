<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = About::orderBy('id', 'desc')->paginate(10);

        if ($about) {
            return response()->json([
                'status' => 'success',
                'data' => $about
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
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(AboutRequest $request)
    {
        $create_about = new About();
        $create_about->title = $request->title;
        $create_about->details = $request->details;

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('product_images', $filename);

            // Concatenate directory path with filename to create full image path
            $imagePath = 'product_images/' . $filename;

            // Add image path to the $add_slide object
            $create_about->image = $imagePath;
        }

        $create_about->save();
        if ($create_about) {
            return response()->json([
                'status' => 'success',
                'data' => $create_about
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
        $singel_brand = About::where('id', $id)->first();

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
        $update_about = About::find($id);

        // Update category_id
        $update_about->title = $request->title ?? $update_about->title;
        $update_about->details = $request->details ?? $update_about->details;

        // Check if a new image has been uploaded
        if ($request->hasFile('image')) {
            // Get the file from the request
            $file = $request->file('image');

            // Get the extension of the file
            $extension = $file->getClientOriginalExtension();

            // Generate a unique filename
            $filename = time() . '.' . $extension;

            // Move the uploaded file to the desired directory
            $file->move('product_images', $filename);

            // Concatenate directory path with filename to create full image path
            $imagePath = 'product_images/' . $filename;

            // Unlink the existing image if it exists
            if ($update_about->image && file_exists(public_path($update_about->image))) {
                unlink(public_path($update_about->image));
            }

            // Set the new image path
            $update_about->image = $imagePath;
        } elseif ($request->has('preview_image')) {
            // If no new image is uploaded but a preview image is provided
            $update_about->image = $request->preview_image;
        }

        // Save the updated slide
        $update_about->save();

        // Check if the slide was successfully updated
        if ($update_about) {
            return response()->json([
                'status' => 'success',
                'data' => $update_about
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
        $About = About::find($id);

        // Check if the record exists
        if ($About) {
            // Check if the image exists
            if ($About->image) {
                $imagePath = $About->image;

                // Check if $imagePath is a valid path
                if (file_exists(public_path($imagePath))) {
                    // Delete the image file
                    unlink(public_path($imagePath));
                }
            }

            // Delete the record
            $delete_about = $About->delete();

            if ($delete_about) {
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
