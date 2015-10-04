<?php

set_time_limit(0);

$folder = trim( $argv[1] );
if( strlen($folder) < 1 ) die('bad args');


$path = $folder;
$path = str_replace('..', '', $path);

$data['base'] = 'comic/'.$path.'/';
$files = './'.$data['base'] . '*.{jpg,png,jpeg}';

//echo $files;

foreach (glob( $files , GLOB_BRACE ) as $filename) {
    echo "dealing ... $filename\r\n";
    resizeImage( $filename , 2000 , 1080 );

}

echo 'done';

function resizeImage($filename, $max_width, $max_height)
{
    $ext = strtolower(end(explode('.',$filename))) ;
    

    list($orig_width, $orig_height) = getimagesize($filename);

    $width = $orig_width;
    $height = $orig_height;

    # taller
    if ($height > $max_height) {
        $width = ($max_height / $height) * $width;
        $height = $max_height;
    }

    # wider
    if ($width > $max_width) {
        $height = ($max_width / $width) * $height;
        $width = $max_width;
    }

    $image_p = imagecreatetruecolor($width, $height);

    if( $ext == 'png' )
        $image = imagecreatefrompng($filename);
    else
        $image = imagecreatefromjpeg($filename);

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, 
                                     $width, $height, $orig_width, $orig_height);

    if( $ext == 'png' )
        imagepng($image_p,$filename);    
    else
        imagejpeg($image_p,$filename);
    
    imagedestroy($image_p);
    imagedestroy($image);
}