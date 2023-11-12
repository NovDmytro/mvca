<?php
/**
 * @var string $someString
 * @var array $modelExample
 */
?>
<h2>Basics MVC sample</h2>

// We had $view['someString']='Some string data'; in controller, lets view this from: echo $someString;<br>
// $someString: <br>
<?= $someString ?><br>
<br>

// We had $view['modelExample']=$example; in controller,  lets view this from foreach ($modelExample)<br>
// $modelExample:<br>
    <?php foreach ($modelExample as $key=>$value) : ?>
        <?= $key ?> = <?= $value ?><br>
    <?php endforeach ?>
<br>

// Translator example: let's translate something via syntax &#123;&#123;CURLANG&#125;&#125;<br>
// Current language is:<br>
{{CURLANG}}<br>
<br>


<a href="/Samples-main">Back to Samples</a>