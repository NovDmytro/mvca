<?php
use Engine\Container;
use Engine\Router;
use Engine\Debug; //Singleton to initialize and reuse type: Debug::init();
use Services\Util;
use Services\Logger;
use Services\Config;
use Services\Database;
use Services\Output;
use Services\Crypto;
use Services\Cookies;
use Services\Request; //Singleton to initialize and reuse type: Request::init();

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
require('system/config.php');
$config = new Config($settings[ENVIRONMENT]);

// Debug
if($config->get('debug')){$debug = Debug::init();$debug->setStatus(true);};

// Load MVC namespaces
$modules = scandir('src/');
$modules = array_filter($modules, function ($folder) {
    return !in_array($folder, ['.', '..']);
});
foreach ($modules as $module) {
    $loader->addNamespace($module.'\\C', 'src/' . $module . '/C');
    $loader->addNamespace($module.'\\M', 'src/' . $module . '/M');
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
$router = new Router($request->GET('route','latin'),$config->get('routerErrorPages'));
$pathData = $router->parsePath();
$method = $pathData['method'];
require_once($pathData['file']);
$config->set('route',$pathData['route']);

// Container
/*
 * working examples:
 *  Config::class => fn () => $config,     //Not lazy load
 *  Crypto::class => fn () => new Crypto($config->get('crypto_key')),     //Lazy load
 *  Test::class => function () {$test = new Test(1);$test->two(2);return $test;},    //Lazy load
 *  Database::class => function () use ($config) {$database = new Database($config->get('DSN'));$database->init();return $database;}, //Lazy load with use
OR  Database::class => fn () => (function ($config) {$database = new Database($config->get('DSN'));$database->init();return $database;})($config),
 */
$container = new Container([
    Config::class => fn () => $config,
   Cookies::class => fn () => new Cookies($config->get('cookiesExpiresTime')),
    Output::class => fn () => new Output($config->get('defaultHeader'), $config->get('defaultFooter'), $config->get('defaultLanguage')),
  Database::class => fn () => (function ($config) {$database = new Database($config->get('DSN'));$database->init();return $database;})($config),
      Util::class => fn () => new Util(),
    Crypto::class => fn () => new Crypto($config->get('crypto_key')),
]);
$controller = $container->get($pathData['class']);
$controller->$method();