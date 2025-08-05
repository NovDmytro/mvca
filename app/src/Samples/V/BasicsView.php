<?php
/**
 * @var string $someString
 * @var array $modelExample
 */
?>
<h2>Basics MVC sample</h2>
<br>
<p>// We had $view['someString']='Some string data'; in controller, lets view this from: echo $someString;</p>
<p>// $someString: </p>
<p><?= $someString ?><</p>
<br>
<p>// We had $view['modelExample']=$example; in controller,  lets view this from foreach ($modelExample)</p>
<p>// $modelExample:</p>

    <?php foreach ($modelExample as $key=>$value) : ?>
        <p><?= $key ?> = <?= $value ?></p>
    <?php endforeach ?>

<br>
<p>// Translator example: let's translate something via syntax &#123;&#123;CURLANG&#125;&#125;</p>
<p>// Current language is: {{CURLANG}</p>
<br>
<a href="/Samples.main">Back to Samples</a>
