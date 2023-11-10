<?php
/**
 * @var string $title
 * @var array $storage
 */
?>
<h1><?= $title ?></h1>
<?php foreach ($storage as $key => $value) : ?>
    <?= $key ?> = <?= $value ?><br>
<?php endforeach ?>