<?php

spl_autoload_register(function ($name) {
  require_once dirname(__FILE__).'/'.strtolower($name).'.class.php';
});
