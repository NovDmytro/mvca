<?php
/*
 * Buy default, framework has reserved URLs in app/public/.htaccess for example:
 *  RewriteRule ^(media|robots.txt|favicon.ico)/ - [L]
 *
 * Routes example:
 *     "welcome" => "Welcome-Welcome-main",
 *     "welcome" => "Welcome-main",
 * If your controllers folder name equals controller name you can skip one name
 * For example:
 * If you have src/Welcome/WelcomeController.php you can use Welcome-main and Welcome-Welcome-main
 * If you have src/Welcome/HelloController.php you must use full name Welcome-Hello-main
*/
return [
    "" => "Index-Index-main",
    "welcome" => "Index-Index-main",

    //common routes
    "error404" => "Core-Errors-Error-e404",
];
