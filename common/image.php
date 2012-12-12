<?php

$imagetype = array(
  IMAGETYPE_GIF=>'gif',
  IMAGETYPE_JPEG=>'jpeg',
  IMAGETYPE_PNG=>'png',
  IMAGETYPE_WBMP=>'bmp'
);

$path = array_key_exists('PATH_INFO', $_SERVER) ?  dirname(__FILE__).'/../img/'.substr($_SERVER['PATH_INFO'], 1) : die();

list($width, $height, $type) = getimagesize($path);
$w = array_key_exists('width', $_REQUEST) ? $_REQUEST['width'] : 100;
$h = array_key_exists('height', $_REQUEST) ? $_REQUEST['height']: $w * $height / $width;
header('Content-type: '.image_type_to_mime_type($type));

$load_image_func = 'imagecreatefrom'.$imagetype[$type];
$generate_image_func = 'image'.$imagetype[$type];

$md5file = md5_file($path);
$tname = str_replace('/', '_', $path);
if (file_exists("cache/{$tname}_{$md5file}")) {
  $tpath = "cache/{$tname}_{$md5file}";
  list($tw, $th) = getimagesize($tpath);
  if ($tw == $w && $th == $h) {
    $gen = $load_image_func($tpath);
    $generate_image_func($gen);
    imagedestroy($gen);
    exit(0);
  }
}
$gen = imagecreate($w, $h);
$org = $load_image_func($path);
imagecopyresampled($gen, $org, 0, 0, 0, 0, $w, $h, $width, $height);
$generate_image_func($gen, "cache/{$tname}_{$md5file}");
$generate_image_func($gen);
imagedestroy($gen);
imagedestroy($org);
