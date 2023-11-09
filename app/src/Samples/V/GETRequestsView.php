<h2>GET Requests samples</h2>

<a href="<?= $getDemoURL ?>">Click <?= $getDemoURL ?> to visit sample url</a><br><br>
First dir is reserved for routing. Rest 6 is under your control, you can put there everything you want.<br>
Also, you can customize it in app/public/.htaccess<br>
Please visit app/src/Samples/C/RequestsController.php and app/src/Samples/V/GETRequestsView.php to understand how its work<br>
Important to init request singleton in method before use: $request = Request::init();<br><br>

$request->GET('route') is: <?= $dirs['route'] ?><br>
$request->GET('var1') is: <?= $dirs['var1'] ?><br>
$request->GET('var2') is: <?= $dirs['var2'] ?><br>
$request->GET('var3') is: <?= $dirs['var3'] ?><br>
$request->GET('var4') is: <?= $dirs['var4'] ?><br>
$request->GET('var5') is: <?= $dirs['var5'] ?><br>
$request->GET('var6') is: <?= $dirs['var6'] ?><br>
$request->GET('querySample1') is: <?= $query['querySample1'] ?><br>
$request->GET('querySample2') is: <?= $query['querySample2'] ?><br>
$request->GET('intFilterSample','int') is: <?= $query['intFilterSample'] ?><br>

<br><br>
Also, you can add filters (int,dec,hex,email,latin,varchar,html) and case switch (low,up),<br>
  examples: $request->GET('example','email','low'); $request->GET('id','int');<br>
You can use: $request->GET $request->POST $request->COOKIE $request->SERVER $request->JSON<br>
