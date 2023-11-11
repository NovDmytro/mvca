# MVCA - ultra lightweight MVC PHP framework

MVCA is a lightweight PHP framework designed for web development. It follows the MVC (Model-View-Controller) architecture in a simple and user-friendly manner, providing essential features for building web applications.

# Key Features
1. Framework Foundation
 - MVCA serves as a foundation for your PHP projects, offering a streamlined approach to web development.

2. Architecture - MVC
 - The framework is built on the MVC architecture, separating the application logic into Model, View, and Controller components.

3. Routing
 - Define and manage your application's routes easily by configuring them in the routes.php file or use dynamic routing without losing functionality.

4. Debugging Console
 - MVCA comes equipped with its own debugging console, allowing you to monitor and troubleshoot your application and database efficiently and fast.

5. Multilingual Support
 - Internationalize your application effortlessly with MVCA's built-in support for multiple languages.

6. Database Interaction
 - Easily manipulate data with MySQL/MariaDB/PostgreSQL using the framework's database functionalities.

7. Security Measures
 - MVCA incorporates robust security measures to safeguard your application against prevalent threats, ensuring a secure environment for your users. To enhance security, MVCA, by default, disables direct access to $_GET, $_POST, $_SERVER and $_COOKIES. Instead, it enforces disciplined data handling through a dedicated singleton with additional JSON wrapper.
Notably, the framework ensures that all user input undergoes thorough filtering based on specified criteria, contributing to a disciplined approach in handling data.

# Contributors

- **Novoselskyi Dmytro**
  - Backend Development
  - Architecture
  - Software Testing
  - Documentation
  - Project Management
    - Contacts:
    - Email: [novosdmytro@gmail.com](mailto:novosdmytro@gmail.com)
    - LinkedIn: [Novoselskyi Dmytro](https://www.linkedin.com/in/dmytro-novoselskyi-b19870290/)

- **Voronenko Evhen**
  - Frontend Development
  - Backend Development
  - Software Testing
  - Documentation
  - Collaborative Project Contribution
    - Contacts:
    - Email: [voronenkotg@gmail.com](mailto:voronenkotg@gmail.com)
    - LinkedIn: [Voronenko Evhen](https://www.linkedin.com/in/evhen-voronenko-52a099121/)

# About Us

Thank you for exploring our pilot project! We are passionate individuals dedicated to creating innovative solutions in the realm of web development. Currently seeking exciting job opportunities or potential business collaborations to apply and expand our skills.

If you have compelling opportunities or business proposals, feel free to reach out to us through our LinkedIn profiles. We look forward to connecting with like-minded professionals and contributing our expertise to meaningful projects.

Let's create something extraordinary together!

# Getting Started
Explore the framework's components and structure to kickstart your web development journey. Refer to the documentation for detailed information on usage, customization, and advanced features.

# Installation

1. Docker Container
Download the repository files and use the Docker container via the terminal command docker compose up. Alternatively, run the provided .bat file (run-windows.bat) containing the same command. Ensure that Docker or Docker CLI is installed on your system.

Requirements:

 - Docker/Docker CLI

2. Apache Server
You can also run the framework on your own Apache server. Ensure that your system meets the following minimum requirements:

Requirements:

 - mod_rewrite
 - PHP 8.1 or highter
 - MySQL/MariaDB/PostgreSQL for data manipulation

# Documentation

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
