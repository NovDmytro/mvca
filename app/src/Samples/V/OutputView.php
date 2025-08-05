<?php
/**
 * @var string $sampleString
 * @var string $sampleArray
 */
?>
<h2>Output sample</h2>
<p>// Because of extract($view); in Output class your array keys of $view became multiple variables</p>
<p>// $view['sampleString'] became $sampleString</p>
<p>// $view['sampleArray'] became $sampleArray</p>
<p>// $sampleString is: <?= $sampleString ?></p>
<br>
<p>// var_dump($sampleArray) is:</p>
<p><?php var_dump($sampleArray) ?></p>
<br>
<a href="/Samples.main">Back to Samples</a>