<?php

use Engine\Console;
use Engine\Container;
use Engine\Router;
use Engine\Debug; //Singleton to initialize and reuse type: Debug::init();
use Engine\Logger;
use Engine\Config;
use Engine\Output;

use Services\Util;
use Services\Database;
use Services\Crypto;
use Services\Cookies;
use Services\Request; //Singleton to initialize and reuse type: Request::init();

use Services\Controller; //Allows to include nested controllers in view
// Autoloader
$loader = new AutoLoader();
$loader->register();
$loader->addNamespace("Engine", "system/Engine");
$loader->addNamespace("Services", "system/Services");
$loader->addNamespace("Core", "system/Core");

// Request
/*
 * $_GET $_POST $_COOKIE $_SERVER and custom $this->JSON wrapper.
 * To Use requests please type: $request = \Services\Request::init();
 * before using in any function. Then $request->GET('example'); will contain your data
 * also You can add filters (int,dec,hex,email,latin,varchar,html) and case (low,up), examples:
 * $request->GET('example','email','low'); $request->GET('id','int');
 */
$request = Request::init();

// Settings
$settings = [];
require('config.php');
$config = new Config($settings[ENVIRONMENT]);

// Debug
$debug=false;
if($config->get('debug')){$debug = Debug::init();$debug->setStatus(true);}

// Load MVC namespaces
$modules = scandir($config->get('sourcesPath'));
$modules = array_filter($modules, function ($folder) {
    return !in_array($folder, ['.', '..']);
});
foreach ($modules as $module) {
    $loader->addNamespace($module.'\\C', $config->get('sourcesPath') . $module . '/C');
    $loader->addNamespace($module.'\\M', $config->get('sourcesPath') . $module . '/M');
    $loader->addNamespace($module.'\\A', $config->get('sourcesPath') . $module . '/A');
}

// Logger
$logger = new Logger(
    $config->get('logPathFatalError'),
    $config->get('logPathNotice'),
    $config->get('logPathWarning'),
    $config->get('logPathUnknownError')
);
set_error_handler(array($logger, 'errorHandler'), E_ALL);
error_reporting(E_ALL);
set_exception_handler(array($logger, 'exceptionHandler'));

// Timezone
date_default_timezone_set($config->get('defaultTimezone'));

// Import the controller

$router = new Router(
    $request->GET('route','latin'),
    $config->get('routesPath'),
    $config->get('sourcesPath'),
    $config->get('routerErrorPages')
);
$pathData = $router->parsePath();
$method = $pathData['method'];
require_once($pathData['file']);
$config->set('route',$pathData['route']);
$config->set('routeTarget',$pathData['target']);

// Container
$container = new Container([
    Config::class => fn () => $config,
   Cookies::class => fn () => new Cookies($config->get('cookiesExpires')),
    Output::class => fn () => new Output($config),
  Database::class => fn () => (function ($config) {$database = new Database($config->get('dsn'));$database->init();return $database;})($config),
      Util::class => fn () => new Util(),
    Crypto::class => fn () => new Crypto($config->get('crypto_key')),
]);

try {
    $controller = $container->get($pathData['class']);
    $controller->$method();
} catch (\ReflectionException $e) {
    die($e);
}

// Debug console
if($debug->enabled()){
    $output=new Output($config);
    $console=new Console($config);
    $content = $output->loadFile('system/Core/Console/V/Console.php', $console->render());
    $content = $output->translateContent($content);
    echo $content;
}