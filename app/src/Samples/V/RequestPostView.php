<?php
/**
 * @var array $postSample
 */
?>
<h2>POST Request samples</h2>
<p>//To understand how Request work please fill up and submit form:</p>
<form method="post">
    <label>Transform to upper case <input type="text" name="upCaseSample" value="FooBar=);+1#%*"></label> <br>
    <label>Email transform <input type="text" name="emailSample" value="email@example.com=);+1#%*"></label><br>
    <label>Leave integers <input type="text" name="filteredNumbersSample" value="-1 2+3 AbC =);+1#%*"></label><br>
    <button>Submit</button>
</form>
<p>//If you submitted previous form now you can see this data:</p>
<p>$postSample['upCaseSample']: <?= $postSample['upCaseSample'] ?></p>
<p>$postSample['emailSample']: <?= $postSample['emailSample'] ?></p>
<p>$postSample['filteredNumbersSample']: <?= $postSample['filteredNumbersSample'] ?></p>
<br>
<a href="/Samples.main">Back to Samples</a>