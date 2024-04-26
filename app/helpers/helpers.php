<?php
//function saveImage($request)
//{
//    $image = $request->file('image');
//    $imageName = rand() . '.' . $image->getClientOriginalExtension();
//    $directory = 'adminAsset/image/';
//    $imgUrl = $directory . $imageName;
//    $image->move($directory, $imageName);
//    return $imgUrl;
//}

function saveImage($request, $fileType)
{
    $file = $request->file($fileType);
    $imageName = rand() . '.' . $file->getClientOriginalExtension();
    $directory = 'adminAsset/image/';
    $imgUrl = $directory . $imageName;
    $file->move($directory, $imageName);
    return $imgUrl;
}


// Function to remove an image
function removeImage($imagePath)
{
    // Check if the file exists before attempting to delete it
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

function productResponse($product_list)
{
    $formated_product_list = $product_list->map(function ($product){
        $product->color = json_decode($product->color);
        $product->size = json_decode($product->size);
        return $product;
    });

    // Return response with paginated product list and additional information
    return response()->json([
        'message' => 'Products',
        'current_page' => $product_list->currentPage(),
        'data' => $formated_product_list,
        'first_page_url' => $product_list->url(1),
        'from' => $product_list->firstItem(),
        'last_page' => $product_list->lastPage(),
        'last_page_url' => $product_list->url($product_list->lastPage()),
        'links' => $product_list->links(),
        'next_page_url' => $product_list->nextPageUrl(),
        'path' => $product_list->url($product_list->currentPage()),
        'per_page' => $product_list->perPage(),
        'prev_page_url' => $product_list->previousPageUrl(),
        'to' => $product_list->lastItem(),
        'total' => $product_list->total(),
    ]);
}
