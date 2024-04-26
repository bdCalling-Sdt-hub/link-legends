<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    //
    public function ourPicks()
    {
       $product_list = Product::with('category','sub_category','product_image')->where('our_picks',true)->paginate(9);
       return productResponse($product_list);
    }

    public function trendingProducts()
    {
        $trending_products = Product::with('category','sub_category','product_image')->orderBy('view_count','desc')->paginate(9);
        return productResponse($trending_products);
    }
}
