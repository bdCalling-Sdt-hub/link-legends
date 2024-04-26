<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FAQRequest;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $FAQ = FAQ::orderBy('id', 'desc')->paginate(10);

        if ($FAQ) {
            return response()->json([
                'status' => 'success',
                'data' => $FAQ
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
    public function store(FAQRequest $request)
    {
        $create_faq = new FAQ();
        $create_faq->question = $request->question;
        $create_faq->answare = $request->answare;
        $create_faq->save();
        if ($create_faq) {
            return response()->json([
                'status' => 'success',
                'data' => $create_faq
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
        $delete_faq = FAQ::where('id', $id)->delete();

        if ($delete_faq) {
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
    }
}
