<h2>POST Requests samples</h2>

Same algorithm as GET, but for POST. Submit data and see results. <br>

<form name="form1" method="post" action="">
    upCaseSample <input type="text" name="upCaseSample" value="Foo"><br>
    emailSample <input type="text" name="emailSample" value="email@example.com"><br>
    filteredNumbersSample <input type="text" name="filteredNumbersSample" value="-1 2+3 AbC"><br>
    <input type="submit" name="Submit" value="Submit to see"><br>
</form>

<br><br>


Results:<br>

$request->POST('upCaseSample','varchar','up') is: <?= $postSample['upCaseSample'] ?><br>
$request->POST('emailSample','email','low') is: <?= $postSample['emailSample'] ?><br>
$request->POST('filteredNumbersSample','int','low') is: <?= $postSample['filteredNumbersSample'] ?><br>

<br><br>
$request->POST('emailSample', 'email', 'low') is using FILTER_SANITIZE_EMAIL.<br>
This filter does not validate your email; it simply cleans up bad characters.