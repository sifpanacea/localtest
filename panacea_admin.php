<?php
#require_once(ImageMagic);
// Note: $image is an Imagick object, not a filename! See example use below.
function autoRotateImage($image) {
    $orientation = $image->getImageOrientation();
echo print_r($orientation, true);
    switch($orientation) {
        case imagick::ORIENTATION_BOTTOMRIGHT:
            $image->rotateimage("#000", 180); // rotate 180 degrees
        break;

        case imagick::ORIENTATION_RIGHTTOP:
            $image->rotateimage("#000", 90); // rotate 90 degrees CW
        break;

        case imagick::ORIENTATION_LEFTBOTTOM:
            $image->rotateimage("#000", -90); // rotate 90 degrees CCW
        break;
    }

    // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
    $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
}

$img = '/var/www/html/PaaS/healthcare/uploaddir/public/uploads/healthcare2016226112942701_con/photo/a6eb9b89ed0f8ebff4233006592921bd.png';
$image = new \Imagick($img);
autoRotateImage($image);
// - Do other stuff to the image here -
$image->writeImage('a6eb9b89ed0f8ebff4233006592921b.jpg');
?>
