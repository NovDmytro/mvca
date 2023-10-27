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

/*
 * $_GET $_POST $_COOKIE $_SERVER and custom $this->JSON wrapper.
 * To Use requests please type: $request = \Services\Request::init();
 * before using in any function. Then $request->GET('example'); will contain your data
 * also You can add filters (int,dec,hex,email,latin,varchar,html) and case (low,up), examples:
 * $request->GET('example','email','low'); $request->GET('id','int');
 */
$request = Request::init();

// Load settings
$settings = [];
require('system/config.php');
$config = new Config($settings[ENVIRONMENT]);

// Load modules
$modules = scandir('src/');
$modules = array_filter($modules, function ($folder) {
    return !in_array($folder, ['.', '..']);
});
foreach ($modules as $module) {
    $loader->addNamespace($module.'\\C', 'src/' . $module . '/C');
    $loader->addNamespace($module.'\\M', 'src/' . $module . '/M');
}

// Logger
$logger = new Logger($config->get('logPathFatalError'), $config->get('logPathNotice'), $config->get('logPathWarning'), $config->get('logPathUnknownError'));
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
$settings[ENVIRONMENT]['route']=$pathData['route'];

// Container
//$database = new Database($config->get('database_url'));
//$database->initialize();
$container = new Container([
    Config::class => fn () => new Config($settings[ENVIRONMENT]),
    //  Database::class => fn () => new Database($config->get('database_url')),
    Output::class => fn () => new Output($config->get('defaultHeader'), $config->get('defaultFooter'), $config->get('defaultLanguage'), $config->get('debugMode')),
    Cookies::class => fn () => new Cookies($config->get('cookiesExpiresTime')),
    Util::class => fn () => new Util(),
    Crypto::class => fn () => new Crypto($config->get('crypto_key')),
]);
$controller = $container->get($pathData['class']);
call_user_func([$controller, $method]);