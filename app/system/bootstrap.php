<?php
use Engine\Container;
use Engine\Router;
use Service\Util;
use Service\Logger;
use Service\Config;
use Service\Database;
use Service\Output;
use Service\Crypto;
use Service\Cookies;
use Service\Request;

$request = Request::start();

$settings = [];
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
$output = new Output($config->get('defaultHeader'), $config->get('defaultFooter'), $config->get('defaultLanguage'), $config->get('cacheVersion'), $config->get('debugMode'));
$logger = new Logger($config->get('log_path_error'), $config->get('log_path_notice'), $config->get('log_path_warning'), $config->get('log_path_unknown_error'));
$cookies = new cookies($config->get('cookiesExpiresTime'));


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
date_default_timezone_set($config->get("defaultTimezone"));

// Crypto
$crypto = new Crypto($config->get('crypto_key'));
$config->set('crypto_key', NULL);
$container->set("crypto", $crypto);


// Import the controller
$router = new Router($request->GET('route','latin','low'));
$path_data = $router->parsePath();

$method = $path_data["method"];
require_once $path_data["file"];
$controller = new $path_data["class"]();
