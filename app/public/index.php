<?php
//$SCRIPT_MEM = memory_get_usage();$SCRIPT_TIME = microtime(true);
chdir(dirname(__DIR__));
define('ROOT', dirname(__DIR__));
define('ENVIRONMENT', 'development');
require "system/engine/AutoLoader.php";

$loader = new AutoLoaderClass();
$loader->register();
$loader->addNamespace("Engine", "system/engine");
$loader->addNamespace("Service", "system/services");

require "system/bootstrap.php";

 $controller->$method();
/*
$MEM=memory_get_usage() - $SCRIPT_MEM;
$TIME=microtime(true) - $SCRIPT_TIME;
    echo 'GENERATION TIME: '.$TIME.' sec';
    echo ' MEMORY USED: '.$MEM.' bytes';*/