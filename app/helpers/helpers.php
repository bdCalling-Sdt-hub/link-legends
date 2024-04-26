<?php
function saveImage($request)
{
    $image = $request->file('image');
    $imageName = rand() . '.' . $image->getClientOriginalExtension();
    $directory = 'adminAsset/image/';
    $imgUrl = $directory . $imageName;
    $image->move($directory, $imageName);
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
