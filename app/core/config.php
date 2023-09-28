<?php

define("WEBSITE_TITLE", 'MY STORE');
define('DBHOST', "localhost");
define('DBNAME', "eshopper_db");
define('DBUSER', "root");
define('DBPASS', "");
define('DBTYPE', "mysql");


define('THEME', 'eshopper/');


define('DEBUG', true);

if (DEBUG) {
	ini_set('display_errors', 1);
}
else
{
	ini_set('display_errors', 0);
}