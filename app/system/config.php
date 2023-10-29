<?php
$settings['development'] = [
    //General configuration
    /* DSN - dataSourceName - Data to connect to your database.
     * Examples:
     * postgresql: pgsql://user:pass@localhost:5432/database
     * mariadb: mysql://user:pass@localhost:3306/database
     * mysql: mysql://user:pass@localhost:3306/database
     *
     * if You run default framework docker compose
     * mariadb    - mysql://mvca:pass@mvca-mariadb:3306/mvcadb
     * postgresql - pgsql://mvca:pass@mvca-postgresql:5432/mvcadb
     */
    "DSN" => "mysql://mvca:pass@mvca-mariadb:3306/mvcadb",
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