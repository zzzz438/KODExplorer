<?php

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

    return $image_p;

    if( $ext == 'png' )
        imagepng($image_p,$filename);    
    else
        imagejpeg($image_p,$filename);
    
    imagedestroy($image_p);
    imagedestroy($image);
}

//$data['base'] = '/comic/kissis01/';
$path = trim($_REQUEST['url']);
$path = str_replace('..', '', $path);

$data['base'] = 'comic/'.$path.'/';

$cachefile_name = 'cache/' . md5($data['base']).'.json';

if( file_exists($cachefile_name) )
{
	echo file_get_contents($cachefile_name);
	exit;	
}


$files = './'.$data['base'] . '*.{jpg,png,jpeg}';

//echo $files;

foreach (glob( $files , GLOB_BRACE ) as $filename) {
    resizeImage( $filename , 2000 , 1080 );
    $data['image'][] = basename($filename);
}

natsort($data['image']);

file_put_contents( $cachefile_name , json_encode($data));

echo json_encode($data);