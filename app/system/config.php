<?php
$settings['development'] = [
    //General configuration
    "database_url" => "pdo-mysql://root:123@127.0.0.1:3306/mvcTable",
    "charset" => "UTF-8",
    "crypto_key" => '$secret%#123456',
    "cookiesExpiresTime" => 60 * 60 * 24 * 30,

    //Defaults
    "defaultLanguage" => "uk",
    "defaultTimezone" => "Europe/Kyiv",
    "defaultHeader" => "src/Common/V/Header.php",
    "defaultFooter" => "src/Common/V/Footer.php",

    //Router errors pages
    "routerErrorPages" => [
        "404"=>"Common-Error-error404",
    ],

    "debugMode" => true,
    "cacheVersion" => 0001,

    //Logs
    "logPathWarning" => "logs/warnings.log",
    "logPathFatalError" => "logs/fatalErrors.log",
    "logPathNotice" => "logs/notices.log",
    "logPathUnknownError" => "logs/unknownErrors.log"
];