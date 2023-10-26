<?php
use Engine\Container;
use Engine\Router;
use Services\Util;
use Services\Logger;
use Services\Config;
use Services\Database;
use Services\Output;
use Services\Crypto;
use Services\Cookies;
use Services\Request;

$request = Request::init();

$settings = [];
$config = new Config();
require('system/config/config.php');
foreach ($settings[ENVIRONMENT] as $settingKey => $settingValue) {
    $config->set($settingKey, $settingValue);
}

$modules = scandir('src/');
$modules = array_filter($modules, function ($folder) {
    return !in_array($folder, ['.', '..']);
});
foreach ($modules as $module) {
    $loader->addNamespace($module.'\\C', 'src/' . $module . '/C');
    $loader->addNamespace($module.'\\M', 'src/' . $module . '/M');
}

$util = new Util();
$output = new Output($config->get('defaultHeader'), $config->get('defaultFooter'), $config->get('defaultLanguage'), $config->get('debugMode'));
$logger = new Logger($config->get('log_path_error'), $config->get('log_path_notice'), $config->get('log_path_warning'), $config->get('log_path_unknown_error'));
$cookies = new cookies($config->get('cookiesExpiresTime'));

//$database = new Database($config->get('database_url'));
//$database->initialize();

set_error_handler(array($logger, "myErrorHandler"), E_ALL);
error_reporting(E_ALL);
set_exception_handler(array($logger, "myExceptionHandler"));

// Timezone
date_default_timezone_set($config->get("defaultTimezone"));

// Crypto
$crypto = new Crypto($config->get('crypto_key'));
$config->set('crypto_key', NULL);

// Add container entries
// Please edit ContainerBootstrap static objects if changed/added container vars to make IDEs work proper
$container = new Container();
$container->set("Config", $config);
$container->set("Database", $database);
$container->set("Logger", $logger);
$container->set("Output", $output);
$container->set("Cookies", $cookies);
$container->set("Util", $util);
$container->set("Crypto", $crypto);


// Import the controller
$router = new Router($request->GET('route','latin'),$config->get('routerErrorPages'));
$pathData = $router->parsePath();
require_once($pathData["file"]);
(new $pathData["class"]())->{$pathData["method"]}();