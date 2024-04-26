<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TermsConditionsRequest;
use App\Models\TermsConditions;
use Illuminate\Http\Request;

class TermsConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms_condition = TermsConditions::orderBy('id', 'desc')->first();

        if ($terms_condition) {
            return response()->json([
                'status' => 'success',
                'data' => $terms_condition
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
    public function store(TermsConditionsRequest $request)
    {
        $create_terms = new TermsConditions();
        $create_terms->details = $request->details;
        $create_terms->save();
        if ($create_terms) {
            return response()->json([
                'status' => 'success',
                'data' => $create_terms
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
        $delete_terms = TermsConditions::where('id', $id)->delete();

        if ($delete_terms) {
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
