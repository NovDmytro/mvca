# MVCA alpha version
A light framework for your PHP based backend

## What is MVCA? 
 - **FRAMEWORK** - MVCA light and fast tool to create your web-app. It use well-known architecture MVC in simple and easy to use form;
 - **ROUTER** - setup your predefined routs to pages in `routes.php`;
 - **DEBUGING** - MVCA has own debugging console;
 - **MULTYLANG** - MVCA internationalization ready. Content unjoint from page structure;
 - **DATABASE** - framework can manipulate data with MySql/MAriaDB;
 - **SECURITY** - framework has data protection from most of know attacks SQL Injection, Cross-Site Scripting (XSS), Cross-Site Request Forgery (CSRF), File Upload Vulnerabilities, Session Fixation, Brute Force Attacks

## Installation
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


## Usage

### Routing 
Static routes setup `app/system/config/routes.php`: <br>
```sh
return [
    "" => "index/Index/main",
    "page" => "staticpage/StaticPage/main",
];
```

Here tag "" => "index/Index/main"  - main page you get when arrive to MVCA website
Other tags specify unique pages. 

`"staticpage/StaticPage/main"`: 
1. `staticpage` - folder name in src;
1. `StaticPage` - controller name;
1. `main` - controller function name.


### Internationalization
MVCA has class `Translator` (`app/system/system/services/Translator.php`). It takes parameter $lang which is suitable for existed translation file at `app/system/system/translations/$lang.php` where $lang is file name alike en.php, ua.php, pl.php etc. In file `$lang.php` you specify insertable variables. **???**


<details>
<summary>Next version features<summary>
<blockquote> 

```sh
 - Routing update - automatic routing
 - Database ready - parallel manipulating with all of MySQL/MariaDb, PostgreSQL, MongoDB instead of just MySQL/MariaDb
 - MVCA without View compmonent mode
```
</blockquote>
</details>

