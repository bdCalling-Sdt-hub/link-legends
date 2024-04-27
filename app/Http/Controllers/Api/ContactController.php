<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfficeHotlineRequest;
use App\Models\OfficeHotline;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contact = OfficeHotline::orderBy('id', 'desc')->get();

        if ($contact) {
            return response()->json([
                'status' => 'success',
                'data' => $contact
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
    public function store(Request $request)
    {
        //
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
    public function update(OfficeHotline $request, string $id)
    {
        $check = OfficeHotline::find($id);
        if ($check) {
            // Update category_id
            $update_contact = OfficeHotline::find($id);
            $update_contact->phone = $request->phone ?? $update_contact->phone;
            $update_contact->email = $request->email ?? $update_contact->email;
            $update_contact->address = $request->address ?? $update_contact->address;
            // Save the updated slide
            $update_contact->save();
        } else {
            $update_contact = new OfficeHotline();
            $update_contact->phone = $request->phone ?? $update_contact->phone;
            $update_contact->email = $request->email ?? $update_contact->email;
            $update_contact->address = $request->address ?? $update_contact->address;
            // Save the updated slide
            $update_contact->save();
        }

        // Check if the slide was successfully updated
        if ($update_contact) {
            return response()->json([
                'status' => 'success',
                'data' => $update_contact
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
        //
    }
}
