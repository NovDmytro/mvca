<?php
use Engine\Container;
use Engine\Router;
use Service\Util;
use Service\Logger;
use Service\Config;
use Service\Database;
use Service\Output;
use Service\Crypter;
use Service\Cookies;
use Service\REQ;

$REQ = REQ::start();

$config = new Config();
require('system/config/config.php');
foreach ($settings[ENVIRONMENT] as $settingKey => $settingValue) {
    $config->set($settingKey, $settingValue);
}

$modules = scandir('src/');
array_shift($modules);
array_shift($modules);


foreach ($modules as $module) {
    $loader->addNamespace(ucfirst($module), 'src/' . $module . '/controller');
    $loader->addNamespace(ucfirst($module), 'src/' . $module . '/model');
}

$util = new Util();
$output = new Output($config->get('template_header'), $config->get('template_footer'), $config->get('system_cache_version'), $config->get('system_debug_console'));
$logger = new Logger($config->get('log_path_error'), $config->get('log_path_notice'), $config->get('log_path_warning'), $config->get('log_path_unknown_error'));
$cookies = new cookies();


//$database = new Database($config->get('database_url'));
//$database->initialize();


set_error_handler(array($logger, "myErrorHandler"), E_ALL);
error_reporting(E_ALL);
set_exception_handler(array($logger, "myExceptionHandler"));


// Add container entries
$container = new Container();
$container->set("config", $config);
//$container->set("database", $database);
$container->set("logger", $logger);
$container->set("output", $output);
$container->set("cookies", $cookies);
$container->set("util", $util);

// Timezone
date_default_timezone_set($config->get("system_default_time_zone"));

$crypter = new Crypter($config->get('crypter_key'));
$config->set('crypter_key', 'null');
$container->set("crypter", $crypter);


// Import the controller
$router = new Router($REQ->GET('route','latin','low'));
$path_data = $router->parsePath();

$method = $path_data["method"];
require_once $path_data["file"];
$controller = new $path_data["class"]();
