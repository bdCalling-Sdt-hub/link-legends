<?php

namespace App\Http\Controllers;

use App\Models\Sub_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $sub_category = Sub_Category::all();
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
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
            $sub_category->image = saveImage($request);
        }
        $sub_category->save();
        return response()->json([
            'message' => 'Sub Category added Successfully',
            'data' => $sub_category
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
