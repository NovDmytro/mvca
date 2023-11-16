<?php
/**
 * @var string $title
 */
?>
<h1  class="center"><?= $title?></h1>

<h2># How this MVC works</h2>
<p>This is MVC example with basic functionality</p>
<p><a href="/Samples-Basics-main/1/">Click:  /Samples-Basics-main/1/</a></p>

<h3>Source code at:</h3>
<p>app/src/Samples/C/BasicsController.php</p>
<p>app/src/Samples/M/BasicsModel.php</p>
<p>app/src/Samples/V/BasicsView.php</p>

<h3>Translations at:</h3>
<p>app/system/translations/</p>

<h2># Routing</h2>
<p>MVCA has app/public/.htaccess that converts first 7 folders except exceptions into query params:</p>
<p>/route/var1/var2/var3/var4/var5/var6/</p>
<p>First folder is used by framework, rest is under your control. </p>
<p>Also, you can add any custom query params in traditional way like this: /route/var1/var2?something=123</p>
<p>This is super simple routing example</p>
<a href="/Samples-Routing-sample/"> Click: /Samples-Routing-sample/</a>

<a href="/routing/">Click:  /routing/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/RoutingController.php</p>
<p>app/src/Samples/V/RoutingView.php</p>

<h3>Routing at:</h3>
<p>app/system/routes.php</p>

<h2># Translation</h2>
<p>This is super simple translation example</p>
<a href="/Samples-Translation-main/?language=en"> Click: /Samples-Translation-main/?language=en</a>

<a href="/Samples-Translation-main/?language=uk"> Click: /Samples-Translation-main/?language=uk</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/TranslationController.php</p>
<p>app/src/Samples/V/TranslationView.php</p>

<h3>Translations at:</h3>
<p>app/translations/</p>

<h2># Customization</h2>
<p>You can customize framework as you with, start from file:</p>
<p>app/system/bootstrap.php</p>

<h2># Output</h2>
<p>To send data from controller to view you need to use Output service.</p>
<a href="/Samples-Output-main/">Click: /Samples-Output-main/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/OutputController.php</p>
<p>app/src/Samples/V/OutputView.php</p>

<h2># Request</h2>
<p>MVCA has disabled $_GET, $_POST, $_SERVER, $_COOKIES.</p>
<p>Instead of them you need to use $request->GET,$request->POST,$request->SERVER,$request->COOKIE and $request->JSON</p>
<p>Before use add this singleton init line: $request = Request::init();</p>
<a href="/Samples-Request-requestGet/">Click: /Samples-Request-requestGet/</a>

<a href="/Samples-Request-requestPost/"> Click: /Samples-Request-requestPost/</a>

<a href="/Samples-Request-requestCookie/">Click:  /Samples-Request-requestCookie/</a>

<a href="/Samples-Request-requestServer/">Click:  /Samples-Request-requestServer/</a>

<a href="/Samples-Request-requestJson/"> Click:  /Samples-Request-requestJson/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/RequestController.php</p>
<p>app/src/Samples/V/RequestGetView.php</p>
<p>app/src/Samples/V/RequestPostView.php</p>
<p>app/src/Samples/V/RequestCookieView.php</p>
<p>app/src/Samples/V/RequestServerView.php</p>
<p>app/src/Samples/V/RequestJsonView.php</p>

<h2># Cookies</h2>
<p>MVCA has simple cookies service that can set, get and delete some client's cookies.</p>
<a href="/Samples-Cookies-main/">Click:  /Samples-Cookies-main/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/CookiesController.php</p>
<p>app/src/Samples/V/CookiesView.php</p>

<h2># Config</h2>
<p>Config class contains all configuration data.</p>
<a href="/Samples-Config-main/">Click:  /Samples-Config-main/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/ConfigController.php</p>
<p>app/src/Samples/V/ConfigView.php</p>

<h2># Database</h2>
<p>Database class can work with PDO mysql, PDO mariadb and PDO postgresql.</p>
<a href="/Samples-Database-main/">Click: /Samples-Database-main/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/M/DatabaseModel.php</p>
<p>app/src/Samples/C/DatabaseController.php</p>
<p>app/src/Samples/V/DatabaseView.php</p>

<h2># Debug</h2>
<a href="/Samples-Debug-main/"> Click: /Samples-Debug-main/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/DebugController.php</p>
<p>app/src/Samples/V/DebugView.php</p>




<h2># WebSocket</h2>
<a href="/Samples-WebSocket-main/"> Click: /Samples-WebSocket-main/</a>

<h3>Source code at:</h3>
<p>app/src/Samples/C/WebSocketController.php</p>
<p>app/src/Samples/V/WebSocketView.php</p>
