<?php
/**
 * @var array $postSample
 */
?>
<h2>POST Request samples</h2>
//To understand how Request work please fill up and submit form:<br>
<form method="post">
    <label>Transform to upper case <input type="text" name="upCaseSample" value="FooBar=);+1#%*"></label> <br>
    <label>Email transform <input type="text" name="emailSample" value="email@example.com=);+1#%*"></label><br>
    <label>Leave integers <input type="text" name="filteredNumbersSample" value="-1 2+3 AbC =);+1#%*"></label><br>
    <button>Submit</button>
</form>

//If you submitted previous form now you can see this data:<br>
$postSample['upCaseSample']: <?= $postSample['upCaseSample'] ?><br>
$postSample['emailSample']: <?= $postSample['emailSample'] ?><br>
$postSample['filteredNumbersSample']: <?= $postSample['filteredNumbersSample'] ?><br>

<br>
<a href="/Samples-main">Back to Samples</a>