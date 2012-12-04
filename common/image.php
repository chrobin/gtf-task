<?php

function load_image($type, $path) {
  switch($type) {
    case IMAGETYPE_GIF:
      $org = imagecreatefromgif($path);
      break;
    case IMAGETYPE_JPEG:
      $org = imagecreatefromjpeg($path);
      break;
    case IMAGETYPE_PNG:
      $org = imagecreatefrompng($path);
      break;
    case IMAGE_WBMP:
      $org = imagecreatefromwbmp($path);
      break;
    default:
      die('Nothing.');
  }
  return $org;
}

function generate_image($type, $gen, $file=NULL) {
  switch($type) {
    case IMAGETYPE_JPEG:
      imagejpeg($gen, $file);
      break;
    case IMAGETYPE_GIF:
      imagegif($gen, $file);
      break;
    case IMAGETYPE_PNG:
      imagepng($gen, $file);
      break;
    case IMAGETYPE_WBMP:
      imagewbmp($gen, $file);
      break;
  }
}

function generate_image_change($path) {
  global $width, $height, $type, $w, $h, $md5file;
  $org = load_image($type, $path);
  $gen = imagecreate($w, $h);
  imagecopyresampled($gen, $org, 0, 0, 0, 0, $w, $h, $width, $height);
  generate_image($type, $gen);
  generate_image($type, $gen, "cache/$md5file");
  imagedestroy($org);
  imagedestroy($gen);
}

$path = array_key_exists('PATH_INFO', $_SERVER) ?  dirname(__FILE__).'/../'.substr($_SERVER['PATH_INFO'], 1) : die();

list($width, $height, $type) = getimagesize($path);
$w = array_key_exists('width', $_REQUEST) ? $_REQUEST['width'] : 100;
$h = array_key_exists('height', $_REQUEST) ? $_REQUEST['height']: $w * $height / $width;

header('Content-type: '.image_type_to_mime_type($type));

$md5file = md5_file($path);
if (file_exists("cache/$md5file")) {
  $tpath = "cache/$md5file";
  list($tw, $th) = getimagesize($tpath);
  if ($tw == $w && $th == $h) {
    $gen = load_image($type, $tpath);
    generate_image($type, $gen);
    imagedestroy($gen);
    die();
  }
} 

generate_image_change($path);
