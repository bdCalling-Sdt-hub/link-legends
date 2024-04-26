<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlideRequest;
use App\Models\Slide;
use Illuminate\Http\Request;

class SecandCarosalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $show_slide = Slide::orderBy('id', 'desc')->get();

        if ($show_slide) {
            return response()->json([
                'status' => 'success',
                'data' => $show_slide
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
    public function store(SlideRequest $request)
    {
        $add_slide = new Slide();
        $add_slide->category_id = $request->category_id;
        $add_slide->url = $request->url;

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('product_images', $filename);

            // Concatenate directory path with filename to create full image path
            $imagePath = 'product_images/' . $filename;

            // Add image path to the $add_slide object
            $add_slide->image = $imagePath;
        }

        $add_slide->save();
        if ($add_slide) {
            return response()->json([
                'status' => 'success',
                'data' => $add_slide
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
        $singel_slide = Slide::where('id', $id)->first();

        if ($singel_slide) {
            return response()->json([
                'status' => 'success',
                'data' => $singel_slide
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
        $update_slide = Slide::find($id);

        // Update category_id
        $update_slide->category_id = $request->category_id ?? $update_slide->category_id;

        // Update URL
        $update_slide->url = $request->url ?? $update_slide->url;

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
            if ($update_slide->image && file_exists(public_path($update_slide->image))) {
                unlink(public_path($update_slide->image));
            }

            // Set the new image path
            $update_slide->image = $imagePath;
        } elseif ($request->has('preview_image')) {
            // If no new image is uploaded but a preview image is provided
            $update_slide->image = $request->preview_image;
        }

        // Save the updated slide
        $update_slide->save();

        // Check if the slide was successfully updated
        if ($update_slide) {
            return response()->json([
                'status' => 'success',
                'message' => 'Slide updated successfully'
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
        $carousel = Slide::find($id);

        // Check if the record exists
        if ($carousel) {
            // Check if the image exists
            if ($carousel->image) {
                $imagePath = $carousel->image;

                // Check if $imagePath is a valid path
                if (file_exists(public_path($imagePath))) {
                    // Delete the image file
                    unlink(public_path($imagePath));
                }
            }

            // Delete the record
            $delete_carousel = $carousel->delete();

            if ($delete_carousel) {
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
