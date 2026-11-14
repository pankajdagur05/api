<?php
// specify the image directory
$target_dir = "";

// get the name of the uploaded file
$target_file=basename($_FILES["fileToUpload"]["name"]);

// get the image type
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// check if the file is an image
 

// create a new image from the uploaded file
if($imageFileType == "jpg" || $imageFileType == "jpeg") {
    $image = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
} elseif($imageFileType == "png") {
    $image = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
} elseif($imageFileType == "gif") {
    $image = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
} else {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    exit;
}

// get the image width and height
$width = imagesx($image);
$height = imagesy($image);

// set the new width and height
$new_width = 100;
$new_height = ($new_width / $width) * $height;

// create a new image with the new dimensions
$new_image = imagecreatetruecolor($new_width, $new_height);

// resize the image
imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// save the resized image to the specified directory
if($imageFileType == "jpg" || $imageFileType == "jpeg") {
    imagejpeg($new_image, $target_file);
} elseif($imageFileType == "png") {
    imagepng($new_image, $target_file);
} elseif($imageFileType == "gif") {
    imagegif($new_image, $target_file);
}

// display a success message
echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
?>
