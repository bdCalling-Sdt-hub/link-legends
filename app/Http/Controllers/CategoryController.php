<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|unique:categories',
            'image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $category = new Category();
        $category->name = $request->name;
        if ($request->file('image')) {
            $category->image = saveImage($request);
        }
        $category->save();
        return response()->json([
            'message' => 'Category added Successfully',
            'data' => $category
        ]);
    }

    public function showCategory()
    {
        $show_category = Category::get();
        if ($show_category) {
            return response()->json([
                'message' => 'success',
                'data' => $show_category
            ], 200);
        } else {
            return response()->json([
                'message' => 'success',
                'data' => []
            ], 200);
        }
    }

    public function updateCategory(Request $request)
    {
        $category = Category::where('id', $request->id)->first();
        if ($category) {
            $validator = Validator::make($request->all(), [
                'name' => 'string|min:2|max:20',
                'image' => ''
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->file('image')) {
                if (!empty($category->image)) {
                    removeImage($category->image);
                }
                $category->image = saveImage($request);
            }
            $category->name = $request->name ?? $category->name;
            $category->update();
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category,
            ]);
        } else {
            return response()->json([
                'message' => 'Category not found',
                'data' => []
            ]);
        }

    }

    public function deleteCategory($id)
    {
        $category = Category::where('id', $id)->first();
        if ($category) {
            $category->delete();
            return response()->json([
                'message' => 'Category deleted successfully',
            ],200);
        }
        return response()->json([
            'message' => 'Category Not Found',
        ],404);
    }
}
