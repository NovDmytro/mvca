<?php
/*
 * Buy default, framework has reserved URLs in app/public/.htaccess for example:
 *  RewriteRule ^(media|robots.txt|favicon.ico)/ - [L]
*/
return [
    //    "URL" => "Folder-Controller-function",
    "" => "Index-Index-main",
    "er" => "Common-Error-error405",
];
