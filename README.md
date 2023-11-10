# MVCA alpha version
A light framework for your PHP project


# Created by
Novoselskyi Dmytro https://www.linkedin.com/in/dmytro-novoselskyi-b19870290/
Voronenko Evhen https://www.linkedin.com/in/evhen-voronenko-52a099121/

# What is MVCA? 
 - **FRAMEWORK** - MVCA light and fast tool to create your web-app. It use well-known architecture MVC in simple and easy to use form;
 - **ROUTER** - setup your predefined routs to pages in `routes.php`;
 - **DEBUGING** - MVCA has own debugging console;
 - **MULTYLANG** - MVCA internationalization ready. Content unjoint from page structure;
 - **DATABASE** - framework can manipulate data with MySql/MAriaDB;
 - **SECURITY** - framework has data protection from most of know attacks SQL Injection, Cross-Site Scripting (XSS), Cross-Site Request Forgery (CSRF), File Upload Vulnerabilities, Session Fixation, Brute Force Attacks

# Installation
1. download files from repo and use docker container via terminal command `docker compose up` <br>
 or <br> 
 run .bat file `run-windows.bat` it contains the same command. <br>

**WARNING!** Must be installed Docker or Docker Cli in  your system.

1.  You can run framework on your own Apache server. 

There are need minimum but not full requirement modules/extensions/configs: **???**
 - xdebug
 - chmod 1777
 - mod_rewrite
 - php 8.1 (not tested in other versions)
 - rights: `chown www-data:www-data -R /var/www`  and `chmod 1777 /var/www`
 - MySql/MariaDB to worck with data


# Usage

## How this MVC works
This is MVC example with basic functionality, to access this code use URL `http://localhost:2121/Products-Storage-main/1`

### Model: `app/src/Products/M/StorageModel.php`
```
<?php
namespace Products\M;
use Services\Database;
class StorageModel
{
    private Database $database;
    public function __construct(
        Database $database,
    )
    {
        $this->database = $database;
    }
    public function getItemById($id): array|bool
    {
        $query=$this->database->query(
            "SELECT * FROM mvca_example WHERE id=:id",
            ['id'=>$id],
            'row'
        );
        return $query;
    }
}
```

### Controller: `app/src/Products/C/StorageController.php`
```
<?php
namespace Products\C;
use Products\M;
use Engine\Config;
use Engine\Output;
use Services\Request;
use Services\Cookies;
class StorageController
{
    private M\StorageModel $storageModel;
    private Config $config;
    private Output $output;
    private Cookies $cookies;
    public function __construct(M\StorageModel $storageModel, Config $config, Output $output, Cookies $cookies)
    {
        $this->storageModel = $storageModel;
        $this->config = $config;
        $this->output = $output;
        $this->cookies = $cookies;
    }
    public function main(): void
    {
        $request = Request::init();
        $view['storage'] = $this->storageModel->getItemById($request->GET('var1', 'int'));
        $view['title'] = '{{Storage sample}} - MVCA';
        $view['config']['charset'] = $this->config->get('charset');
        $this->output->load("Products/Storage", $view, ['language' => $this->cookies->get('language')]);
    }
}
```

### View: `app/src/Products/V/StorageView.php`
```
<?php
/**
 * @var string $title
 * @var array $storage
 */
?>
<h1><?= $title ?></h1>
<?php foreach ($storage as $key => $value) : ?>
    <?= $key ?> = <?= $value ?><br>
<?php endforeach ?>
```

## Routing
MVCA has `app/public/.htaccess` that converts first 7 folders except exceptions into query params `http://localhost:2121/route/var1/var2/var3/var4/var5/var6/`
First folder is used by framework, rest is under your control. Also you can add any custom query params in traditional way like this: `http://localhost:2121/route/var1/var2?something=123`

In previous View example we visit code with url `http://localhost:2121/Products-Storage-main/1`
To make url looks better open `app/system/routes.php` and add this line in return array:
`"products" => "Products-Storage-main",`
Now you can wisit same script form `http://localhost:2121/storage/1`
Where `'storage'` is alias to `'Products-Storage-main'` and `1` is the variable from `$request->GET('var1', 'int')`

To setup index route just leave empty array's key
`"" => "Folder-Controller-method",`

Products-Storage-main routes dinamicly
Where:
`'Products'` is a folder `app/src/Products/`
`'Storage'` is a part of target controller name `app/src/Products/C/StorageController.php`
`'main'` is a target conroller's method

If your conroller's name and folder equal, for example:
`app/src/Example/C/ExampleController.php`
You can use full dinamic url `Example-Example-method` and shortened version `Example-method`

## Translation
MVCA has Translator class `app/system/system/services/Translator.php` 
To translate something you need this steps to do:
1. Open `app/system/config.php` and configurate this parameters:
```
    'defaultLanguage' => 'uk',
    'allowedLanguages' => ['en','uk'],
```
2. Open `app/system/translations/` and create this files `en.php`, `uk.php`. File content example:
```
<?php
return [
    "CURLANG" => "English",
    "Translate me" => "Custom translation example",
];
```

Then in view, constructor, model, or database type `{{Translate me}}` and this will translates to: `Custom translation example`

## Customization
You can edit `app/system/bootstrap.php` and add some custom functionality, this file executes before any request and has lot of congurations


## Basic functionality

### Output
To send data from controller to view you need to use Output service. To wire cookies use container (add to __construct: `Output $output`)
```
$this->output->load(
string 'route',
array 'data',
array 'settings'
 );
```

`'route'` - is your view file route, for example: `'Folder/Example'` for `app/src/Folder/V/ExampleView.php`
`'data'` - is data array to send to view
`'settings'` - not requered, is settings array, that reads `'header'`, `'footer'` and `'language'` keys and redeclare config defaults if set.

### Request
By default, MVCA has disabled `$_GET`, `$_POST`, `$_SERVER`, `$_COOKIES`. Instead of them you need to use this singleton:
Before use add this singleton init line: `$request = Request::init();` Then you can use:

```
$request->GET(
string 'key',
string 'filter',
string 'case'
)
```

`'key'` - is your query key
`'filter'` - not requered, is one of this filters: `'int'`, `'dec'`, `'hex'`, `'email'`, `'latin'`, `'varchar'`, `'html'`. Default is `'varchar'`
`'case'` - not requered, is a case switcher, can be: `'low'`, `'up'`

Same logic is used for: `$request->POST`, `$request->SERVER`, `$request->JSON`, `$request->COOKIES`

`$request->JSON` is used to catch JSON POST data

### Cookies
MVCA has simple cookies service that can set, get and delete some client's cookies. To wire cookies use container (add to __construct: `Cookies $cookies`)

Set new cookie:
```
$this->cookies->set(
string 'key',
string 'value'
)
```

Get client's cookie:
```
$this->cookies->get(
string 'key'
)
```

Delete client's cookie:
```
$this->cookies->del(
string 'key'
)
```

### Config
Config class contains all configuration data. To wire config use container (add to __construct: `Config $config`)
You can add custom config variables by adding it to `app/system/config.php`
Or you can set it somwhere in `app/system/bootstrap.php`

Set new config variable:
```
$this->config->set(
string 'key',
string 'value'
)
```

Get config variable:
```
$this->config->get(
string 'key'
)
```

Delete config variable:
```
$this->config->del(
string 'key'
)
```

Set new multiple variables by array:
```
$this->config->setArray(
array 'data'
)
```

Get all config variables in array:
```
$this->config->getArray()
```

### Database
Database class can work with `PDO mysql`, `PDO mariadb` and `PDO postgresql`. To wire database use container (add to __construct: `Database $database`)
Before use add DSN to: `app/system/config.php`
Example configs:
 For mariadb and mysql: `'mysql://user:pass@host:3306/database?charset=UTF8'`
 For postgresql: `'pgsql://user:pass@host:5432/database?charset=UTF8'`

Sql query:
```
$this->database->query(
string 'sql',
array 'params',
string 'returnType'
)
```

`'sql'` - is your sql query
`'params'` - not requered, array of params, that you used in sql query. Also it can contain array of arrays, in this case you will have multiple requests for each
`'returnType'` - not requered, `'array'` - will return array of rows, `'row'` - will return only first row, `'lastInsertId'` - will return last insert id. Default is `'array'`

Get last insert id in another way:
```
$this->database->getLastId()
```

### Debug
Before use add this singleton init line: `$debug=Debug::init();`
To check if debug mode is enabled use: `if($debug->enabled()){/*Your code here*/}`
To add debug report:

```
$debug->addReport(
string|array 'data',
string 'source',
string 'type')
```

`'data'` - is any data that you want to push to console
`'source'` - class name or any name that will group your reports, can be anything but not null
`'type'` - report type, by default `'Info'`, `'Warning'`, `'Notice'`, `'FatalError'` or `'Unknown'`. But you can write anything you want