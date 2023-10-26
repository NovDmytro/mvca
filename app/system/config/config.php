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
    "defaultHeader" => "src/common/view/Header.php",
    "defaultFooter" => "src/common/view/Footer.php",

    //Router errors pages
    "routerErrorPages" => [
        "404"=>"Common-Error-Error404",
    ],

    "debugMode" => false,
    "cacheVersion" => 0001,

    "log_path_warning" => "system/logs/warning.log",
    "log_path_error" => "system/logs/error.log",
    "log_path_notice" => "system/logs/notice.log",
    "log_path_unknown_error" => "system/logs/unknown_error.log"
];