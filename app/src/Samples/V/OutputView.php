<?php
/**
 * @var string $sampleString
 * @var string $sampleArray
 */
?>
<h2>Output sample</h2>

// Because of extract($view); in Output class your array keys of $view became multiple variables<br>
// $view['sampleString'] became $sampleString<br>
// $view['sampleArray'] became $sampleArray<br>

// $sampleString is:<br>
<?= $sampleString ?><br>

// var_dump($sampleArray) is:<br>
<?php var_dump($sampleArray) ?><br>

<a href="/Samples-main">Back to Samples</a>