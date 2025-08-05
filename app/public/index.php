<?php
chdir(dirname(__DIR__));
define('ROOT', dirname(__DIR__));
define('ENVIRONMENT', 'development');
require_once("system/Engine/AutoLoader.php");
require_once("config/bootstrap.php");