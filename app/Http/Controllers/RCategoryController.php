<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category_name = $request->search;
        $query = Category::query();

        // Apply filters if category is provided
        if ($category_name) {
            $query->where('name', 'like', '%' . $category_name . '%');
        }
        $category = $query->paginate(9);
        return response()->json([
            'message' => 'Categories',
            'data' => $category,
        ]);
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
//    public function store(CategoryRequest $request)
//    {
//        //
//        $category = new Category();
//        $category->name = $request->name;
//        if ($request->file('image')) {
//            $category->image = saveImage($request);
//        }
//        if ($request->file('icon')) {
//            $category->icon = saveImage($request);
//        }
////        $category->icon = $request->icon;
//        $category->save();
//        return response()->json([
//            'message' => 'Category added Successfully',
//            'data' => $category
//        ]);
//    }
    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $category->image = saveImage($request, 'image');
        }

        if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
            $category->icon = saveImage($request, 'icon');
        }

        $category->save();

        return response()->json([
            'message' => 'Category added Successfully',
            'data' => $category
        ]);
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
        $category = Category::where('id', $id)->first();
        if ($category) {
            $validator = Validator::make($request->all(), [
                'name' => 'string|min:2|unique:categories',
                'image' => 'mimes:jpg,png,jpeg,gif,svg|max:2048',
                'icon' => 'mimes:jpg,png,jpeg,gif,svg|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->file('image')) {
                if (!empty($category->image)) {
                    removeImage($category->image);
                }
                $category->image = saveImage($request,'image');
            }
            if ($request->file('icon')) {
                if (!empty($category->image)) {
                    removeImage($category->image);
                }
                $category->image = saveImage($request,'icon');
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

    public function destroy(string $id)
    {
        //
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
