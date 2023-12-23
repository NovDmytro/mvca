<?php
$settings['development'] = [
    //General configuration
    /* dsn Examples
     * mariadb    - mysql://mvca:pass@mvca-mariadb:3306/mvcadb?charset=UTF8
     * postgresql - pgsql://mvca:pass@mvca-postgresql:5432/mvcadb?charset=UTF8
     */
    'dsn' => 'mysql://mvca:pass@mvca-mariadb:3306/mvcadb?charset=UTF8',
    'charset' => 'UTF-8',
    'cryptoKey' => '$secret%#123456',
    'cookiesExpires' => 60 * 60 * 24 * 30,
    'allowedLanguages' => ['en','uk','ru'],
    'sourcesPath'=>'src/',
    'routesPath'=>'config/routes.php',
    'translationsPath'=>'config/translations/',


    //Defaults
    'defaultLanguage' => 'uk',
    'defaultTimezone' => 'Europe/Kyiv',
    'defaultHeader' => 'src/Common/V/Header.php',
    'defaultFooter' => 'src/Common/V/Footer.php',


    //Router errors pages
    'routerErrorPages' => [
        '404'=>'error404',
    ],

    'debug' => true,
    'cacheVersion' => 0001,

    //Logs
    'logPathWarning' => 'logs/warnings.log',
    'logPathFatalError' => 'logs/fatalErrors.log',
    'logPathNotice' => 'logs/notices.log',
    'logPathUnknownError' => 'logs/unknownErrors.log'
];