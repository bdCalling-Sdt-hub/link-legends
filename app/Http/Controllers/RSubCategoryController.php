<?php

namespace App\Http\Controllers;

use App\Models\Sub_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        //
        $sub_category_name = $request->search;
        $query = Sub_Category::query();

        // Apply filters if category is provided
        if ($sub_category_name) {
            $query->where('name', 'like', '%' . $sub_category_name . '%');
        }
        $sub_categories = $query->paginate(9);
        return response()->json([
            'message' => 'Sub Categories',
            'data' => $sub_categories,
        ]);
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_id' => '',
            'name' => 'required|string|min:2|unique:sub_categories',
            'image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $sub_category = new Sub_Category();
        $sub_category->category_id = $request->category_id;
        $sub_category->name = $request->name;
        if ($request->file('image')) {
            $sub_category->image = saveImage($request,'image');
        }
        $sub_category->save();
        return response()->json([
            'message' => 'Sub Category added Successfully',
            'data' => $sub_category
        ]);
    }

    public function show(string $id)
    {
        //
        $sub_category = Sub_Category::where('id',$id)->first();
        if ($sub_category){
            return response()->json([
                'message' => 'Sub Category',
                'status' => 200,
                'data' => $sub_category
            ],200);
        }else{
            return response()->json([
                'message' => 'Sub Category',
                'status' => 404,
                'data' => []
            ],404);
        }
    }

    public function update(Request $request, string $id)
    {
        //
        $sub_category = Sub_Category::where('id', $id)->first();
        if ($sub_category) {
            $validator = Validator::make($request->all(), [
                'name' => 'string|min:2|max:20',
                'image' => ''
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->file('image')) {
                if (!empty($sub_category->image)) {
                    removeImage($sub_category->image);
                }
                $sub_category->image = saveImage($request,'image');
            }
            $sub_category->name = $request->name ?? $sub_category->name;
            $sub_category->update();
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $sub_category,
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
        $category = Sub_Category::where('id', $id)->first();
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
