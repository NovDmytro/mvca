<?php
$settings['development'] = [
    //General configuration
    /* DSN Examples
     * mariadb    - mysql://mvca:pass@mvca-mariadb:3306/mvcadb?charset=UTF8
     * postgresql - pgsql://mvca:pass@mvca-postgresql:5432/mvcadb?charset=UTF8
     */
    "DSN" => "mysql://mvca:pass@mvca-mariadb:3306/mvcadb?charset=UTF8",
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
        "404"=>"error404",
    ],

    "debug" => true,
    "cacheVersion" => 0001,

    //Logs
    "logPathWarning" => "logs/warnings.log",
    "logPathFatalError" => "logs/fatalErrors.log",
    "logPathNotice" => "logs/notices.log",
    "logPathUnknownError" => "logs/unknownErrors.log"
];