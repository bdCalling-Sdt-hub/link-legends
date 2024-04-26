<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrivacyRequest;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $privacy = PrivacyPolicy::orderBy('id', 'desc')->first();

        if ($privacy) {
            return response()->json([
                'status' => 'success',
                'data' => $privacy
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
    public function store(PrivacyRequest $request)
    {
        $create_privacy = new PrivacyPolicy();
        $create_privacy->details = $request->details;
        $create_privacy->save();
        if ($create_privacy) {
            return response()->json([
                'status' => 'success',
                'data' => $create_privacy
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
        $delete_privacy = PrivacyPolicy::where('id', $id)->delete();

        if ($delete_privacy) {
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
