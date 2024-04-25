<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class RContactController extends Controller
{
    public function index()
    {
        //
        $ratings = Contact::paginate(9);
        if ($ratings){
            return response()->json([
                'message' => 'Feedback',
                'data' => $ratings,
            ]);
        }else{
            return response()->json([
                'message' => 'Feedback not found',
                'data' => [],
            ],404);
        }

    }


    public function store(ContactRequest $request)
    {

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->message = $request->message;
        $contact->save();
        return response()->json([
            'message' => 'Thank you for your feedback',
            'data' => $contact
        ]);
    }


    public function show(string $id)
    {
        //
        $contact = Contact::where('id',$id)->first();
        if ($contact)
        {
            return response()->json([
                'message' => 'Feedback',
                'data' => $contact
            ]);
        }else{
            return response()->json([
                'message' => 'Rating does not exist',
                'data' => []
            ],404);
        }
    }


    public function update(Request $request, string $id)
    {
        $contact = Contact::where('id',$id)->first();
        if (empty($contact)){
            return response()->json([
                'message' => 'Feedback does not exist',
                'data' => $contact,
            ]);
        }
        $contact->name = $request->name ?? $contact->name;
        $contact->email = $request->email ?? $contact->email;
        $contact->phone = $request->phone ?? $contact->phone;
        $contact->message = $request->message ?? $contact->message;
        $contact->update();
        return response()->json([
            'message' => 'update your ratings successfully',
            'data' => $contact
        ]);
    }

    public function destroy(string $id)
    {
        //
        $contact = Contact::where('id', $id)->first();
        if ($contact) {
            $contact->delete();
            return response()->json([
                'message' => 'Contact Information deleted successfully',
            ],200);
        }
        return response()->json([
            'message' => 'Contact Information does not found',
        ],404);
    }
}
