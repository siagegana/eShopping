<?php

session_start();

$path = $_SERVER['REQUEST_SCHEME'] . "://".  $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
$path = str_replace("index.php", "", $path);

define('ROOT', $path);
define('ASSETS', $path . "assets/");


require "../app/core/init.php";
//show($_SERVER);
$app = new App();
//$app->loadContr();