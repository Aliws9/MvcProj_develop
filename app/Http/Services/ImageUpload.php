<?php
namespace App\Http\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;



class ImageUpload
    {
    public static function UploadAndFitImage($file, $path, $name, $ext, $width, $height ,$destination)
        {

        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true)) {
                die('cant create directory');
            }
        }

        // create image manager with desired driver
        $manager = new ImageManager(new Driver());

        // // read image from file system
        $image = $manager->read($file['tmp_name'])->scale($width , $height);

        // // resize image to fit within width and height while maintaining aspect ratio and without upsizing
        // $currentWidth = $image->width();
        // $currentHeight = $image->height();

        // $ratio = min($width / $currentWidth, $height / $currentHeight);

        // if ($ratio < 1) {
        //     $newWidth = (int)($currentWidth * $ratio);
        //     $newHeight = (int)($currentHeight * $ratio);
        //     $image->resize($newWidth, $newHeight);
        // }

        

        // save modified image with original name and extension
        $filename = $name . '.' . $ext;
        if($image->save($destination)){
            return true;
        }else{
            return false;
        }

        }
    }