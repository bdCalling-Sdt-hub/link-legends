<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Notification;

class RRatingController extends Controller
{
    public function index()
    {
        //
        $ratings = Rating::paginate(9);
        if ($ratings) {
            return response()->json([
                'message' => 'Feedback',
                'data' => $ratings,
            ]);
        } else {
            return response()->json([
                'message' => 'Feedback not found',
                'data' => [],
            ], 404);
        }
    }

    // public function store(RatingRequest $request)
    // {
    //     $auth_user = auth()->user()->id;
    //     if (!$auth_user) {
    //         return response()->json([
    //             'message' => 'unauthorized user',
    //         ], 401);
    //     }
    //     $ratings = new Rating();
    //     $ratings->user_id = $auth_user;
    //     $ratings->rating = $request->rating;
    //     $ratings->message = $request->message;
    //     $ratings->save();
    //     $auth_user->notify(new AdminNotification($ratings));
    //     return response()->json([
    //         'message' => 'Thank you for your feedback',
    //         'data' => $ratings
    //     ]);
    // }

    public function store(RatingRequest $request)
    {
        // Check if a user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Unauthorized user',
            ], 401);
        }

        // Retrieve the authenticated user
        $auth_user = auth()->user();

        // Check if the user object is valid
        if (!$auth_user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Create and save the rating
        $ratings = new Rating();
        $ratings->user_id = $auth_user->id;
        $ratings->rating = $request->rating;
        $ratings->message = $request->message;
        $ratings->save();

        // Notify the admin
        $auth_user->notify(new AdminNotification($ratings));

        return response()->json([
            'message' => 'Thank you for your feedback',
            'data' => $ratings
        ]);
    }

    public function show(string $id)
    {
        //
        $rating = Rating::where('id', $id)->first();
        if ($rating) {
            return response()->json([
                'message' => 'Feedback',
                'data' => $rating
            ]);
        } else {
            return response()->json([
                'message' => 'Rating does not exist',
                'data' => []
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        $ratings = Rating::where('id', $id)->first();
        if (empty($ratings)) {
            return response()->json([
                'message' => 'Feedback does not exist',
                'data' => $ratings,
            ]);
        }
        $ratings->rating = $request->rating ?? $ratings->rating;
        $ratings->message = $request->message ?? $ratings->rating;
        $ratings->update();
        return response()->json([
            'message' => 'update your ratings successfully',
            'data' => $ratings
        ]);
    }

    public function destroy(string $id)
    {
        //
        $category = Rating::where('id', $id)->first();
        if ($category) {
            $category->delete();
            return response()->json([
                'message' => 'Rating deleted successfully',
            ], 200);
        }
        return response()->json([
            'message' => 'Rating Not Found',
        ], 404);
    }

    public function publishRating(string $id)
    {
        $rating = Rating::where('id', $id)->first();
        if (empty($rating)) {
            return response()->json([
                'message' => 'Rating does not exist',
            ], 404);
        }
        $rating->status = 'published';
        $rating->update();
        return response()->json([
            'message' => 'Rating status update successfully',
            'data' => $rating
        ]);
    }
}
