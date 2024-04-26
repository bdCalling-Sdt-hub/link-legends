<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Blog = Blog::orderBy('id', 'desc')->paginate(10);

        if ($Blog) {
            return response()->json([
                'status' => 'success',
                'data' => $Blog
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
    public function store(BlogRequest $request)
    {
        $create_blog = new Blog();
        $create_blog->title = $request->title;
        $create_blog->details = $request->details;

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('product_images', $filename);

            // Concatenate directory path with filename to create full image path
            $imagePath = 'product_images/' . $filename;

            // Add image path to the $add_slide object
            $create_blog->image = $imagePath;
        }

        $create_blog->save();
        if ($create_blog) {
            return response()->json([
                'status' => 'success',
                'data' => $create_blog
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
        $singel_blog = Blog::where('id', $id)->first();

        if ($singel_blog) {
            return response()->json([
                'status' => 'success',
                'data' => $singel_blog
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
        $update_blog = Blog::find($id);

        // Update category_id
        $update_blog->title = $request->title ?? $update_blog->title;
        $update_blog->details = $request->details ?? $update_blog->details;

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
            if ($update_blog->image && file_exists(public_path($update_blog->image))) {
                unlink(public_path($update_blog->image));
            }

            // Set the new image path
            $update_blog->image = $imagePath;
        } elseif ($request->has('preview_image')) {
            // If no new image is uploaded but a preview image is provided
            $update_blog->image = $request->preview_image;
        }

        // Save the updated slide
        $update_blog->save();

        // Check if the slide was successfully updated
        if ($update_blog) {
            return response()->json([
                'status' => 'success',
                'data' => $update_blog
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update status coustom method.
     */
    public function update_status(Request $request, string $id)
    {
        $update_status = Blog::find($id);
        $update_status->status = $request->status ?? $update_status->status;
        $update_status->save();

        if ($update_status) {
            return response()->json([
                'status' => 'success',
                'message' => 'Update status success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the record
        $Blog = Blog::find($id);

        // Check if the record exists
        if ($Blog) {
            // Check if the image exists
            if ($Blog->image) {
                $imagePath = $Blog->image;

                // Check if $imagePath is a valid path
                if (file_exists(public_path($imagePath))) {
                    // Delete the image file
                    unlink(public_path($imagePath));
                }
            }

            // Delete the record
            $delete_blog = $Blog->delete();

            if ($delete_blog) {
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
