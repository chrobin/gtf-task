<?php

class GtRouter {

  protected static function split_($str) {
    $method = '';
    for ($i=1; $i<strlen($str) && $str[$i] != '/'; $i++) {
      $method .= $str[$i];
    }
    $str = substr($str, $i+1);
    return array("$method", $str);
  }

  public static function bootstrap($cls) {
    // obtain the request uri.
    if (! isset($_SERVER['PATH_INFO'])) {
      $uri = '/index';
    } else {
      $uri = $_SERVER['PATH_INFO']; 
    }

    list($method, $uri) = static::split_($uri);

    $cls->$method($uri);

  }
}

