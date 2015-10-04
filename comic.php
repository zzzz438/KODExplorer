<?php

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
    //resizeImage( $filename , 2000 , 1080 );
    $data['image'][] = basename($filename);
}
natsort($data['image']);

//echo "php convert.php " . trim($_REQUEST['url']) . " &";
$data['cmd'] = $cmd = "php convert.php " . trim($_REQUEST['url']) . " > /dev/null 2>/dev/null & ";
echo exec($cmd);

file_put_contents( $cachefile_name , json_encode($data));
echo json_encode($data);