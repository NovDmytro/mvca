<?php
//$SCRIPT_MEM = memory_get_usage();$SCRIPT_TIME = microtime(true);
chdir(dirname(__DIR__));
define('ROOT', dirname(__DIR__));
define('ENVIRONMENT', 'development');
require_once("system/Engine/AutoLoader.php");
require_once("system/bootstrap.php");
/*
$MEM=memory_get_usage() - $SCRIPT_MEM;
$TIME=microtime(true) - $SCRIPT_TIME;
    echo 'GENERATION TIME: '.$TIME.' sec';
    echo ' MEMORY USED: '.$MEM.' bytes';*/