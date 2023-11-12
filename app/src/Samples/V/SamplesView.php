<?php
/**
 * @var string $title
 */
?>
<h1 style="text-align:center"><?= $title?></h1>

<h2># How this MVC works</h2>
This is MVC example with basic functionality<br>
Click: <a href="/Samples-Basics-main/1/">/Samples-Basics-main/1/</a><br>
Source code at:<br>
app/src/Samples/C/BasicsController.php<br>
app/src/Samples/M/BasicsModel.php<br>
app/src/Samples/V/BasicsView.php<br>
Translations at:<br>
app/system/translations/<br>
<br>

<h2># Routing</h2>
MVCA has app/public/.htaccess that converts first 7 folders except exceptions into query params:<br>
/route/var1/var2/var3/var4/var5/var6/<br>
First folder is used by framework, rest is under your control. <br>
Also, you can add any custom query params in traditional way like this: /route/var1/var2?something=123<br>
This is super simple routing example<br>
Click: <a href="/Samples-Routing-sample/">/Samples-Routing-sample/</a><br>
Click: <a href="/routing/">/routing/</a><br>
Source code at:<br>
app/src/Samples/C/RoutingController.php<br>
app/src/Samples/V/RoutingView.php<br>
Routing at:<br>
app/system/routes.php<br>
<br>

<h2># Translation</h2>
This is super simple translation example<br>
Click: <a href="/Samples-Translation-main/?language=en">/Samples-Translation-main/?language=en</a><br>
Click: <a href="/Samples-Translation-main/?language=uk">/Samples-Translation-main/?language=uk</a><br>
Source code at:<br>
app/src/Samples/C/TranslationController.php<br>
app/src/Samples/V/TranslationView.php<br>
Translations at:<br>
app/translations/<br>
<br>

<h2># Customization</h2>
You can customize framework as you with, start from file:<br>
app/system/bootstrap.php<br>
<br>

<h2># Output</h2>
To send data from controller to view you need to use Output service.<br>
Click: <a href="/Samples-Output-main/">/Samples-Output-main/</a><br>
Source code at:<br>
app/src/Samples/C/OutputController.php<br>
app/src/Samples/V/OutputView.php<br>
<br>


<h2># Request</h2>
MVCA has disabled $_GET, $_POST, $_SERVER, $_COOKIES.<br>
Instead of them you need to use $request->GET,$request->POST,$request->SERVER,$request->COOKIE and $request->JSON<br>
Before use add this singleton init line: $request = Request::init();<br>
Click: <a href="/Samples-Request-requestGet/">/Samples-Request-requestGet/</a><br>
Click: <a href="/Samples-Request-requestPost/">/Samples-Request-requestPost/</a><br>
Click: <a href="/Samples-Request-requestCookie/">/Samples-Request-requestCookie/</a><br>
Click: <a href="/Samples-Request-requestServer/">/Samples-Request-requestServer/</a><br>
Click: <a href="/Samples-Request-requestJson/">/Samples-Request-requestJson/</a><br>
Source code at:<br>
app/src/Samples/C/RequestController.php<br>
app/src/Samples/V/RequestGetView.php<br>
app/src/Samples/V/RequestPostView.php<br>
app/src/Samples/V/RequestCookieView.php<br>
app/src/Samples/V/RequestServerView.php<br>
app/src/Samples/V/RequestJsonView.php<br>
<br>

<h2># Cookies</h2>
MVCA has simple cookies service that can set, get and delete some client's cookies.<br>
Click: <a href="/Samples-Cookies-main/">/Samples-Cookies-main/</a><br>
Source code at:<br>
app/src/Samples/C/CookiesController.php<br>
app/src/Samples/V/CookiesView.php<br>
<br>

<h2># Config</h2>
Config class contains all configuration data.<br>
Click: <a href="/Samples-Config-main/">/Samples-Config-main/</a><br>
Source code at:<br>
app/src/Samples/C/ConfigController.php<br>
app/src/Samples/V/ConfigView.php<br>
<br>

<h2># Database</h2>
Database class can work with PDO mysql, PDO mariadb and PDO postgresql.<br>
Click: <a href="/Samples-Database-main/">/Samples-Database-main/</a><br>
Source code at:<br>
app/src/Samples/M/DatabaseModel.php<br>
app/src/Samples/C/DatabaseController.php<br>
app/src/Samples/V/DatabaseView.php<br>
<br>

<h2># Debug</h2>
Click: <a href="/Samples-Debug-main/">/Samples-Debug-main/</a><br>
Source code at:<br>
app/src/Samples/C/DebugController.php<br>
app/src/Samples/V/DebugView.php<br>
<br>