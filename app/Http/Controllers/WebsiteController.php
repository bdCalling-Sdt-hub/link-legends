<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    //
    public function ourPicks()
    {
       $product = Product::where('our_picks',true)->paginate(9);
       if (empty($product))
       {
           return response()->json([
               'message' => 'No Product List Found',
               'data' => [],
           ]);
       }
       return response()->json([
           'message' => 'Product List Found',
           'data' => $product,
       ]);

    }
}
