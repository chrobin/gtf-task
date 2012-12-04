<?php

spl_autoload_register(function ($name) {
  require_once dirname(__FILE__).'/class/'.strtolower($name).'.class.php';
});
