<?php

namespace App\Http\Controllers;

use App\Models\BookMark;
use Illuminate\Http\Request;

class BookMarkController extends Controller
{
    //

    public function toggleBookmark(Request $request)
    {
        $user_id = auth()->user()->id;
        $product_id = $request->product_id;

        $bookmark = Bookmark::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                'message' => 'Bookmark deleted'
            ]);
        } else {
            $bookmark_info = new BookMark();
            $bookmark_info->user_id = $user_id;
            $bookmark_info->product_id = $request->product_id;
            $bookmark_info->status = true;
            $bookmark_info->save();
            return response()->json([
                'message' => 'Bookmark added',
                'data' => $bookmark_info,
            ]);
        }
    }

    public function wishListData()
    {
        $user_id = auth()->user()->id;
        $bookmarks = Bookmark::with('product','product.category')->where('user_id', $user_id)->paginate(4);
        return response()->json([
            'message' => 'Bookmarked Product list',
            'data' => $bookmarks,
        ]);
    }
}
