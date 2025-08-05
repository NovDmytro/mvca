<?php
/**
 * @var string $route
 */
?>
<h2>Routing sample</h2>
<br>
<p>// We had $view['route']=$this->config->get('route'); in controller, lets view this from: echo $route;</p>
<br>
<p>$route: <?= $route ?></p>
<br>
<?php
if (defined('DYNAMIC_ROUTE')) {echo "DYNAMIC_ROUTE";}
if (defined('STATIC_ROUTE')) {echo "STATIC_ROUTE";}
?>
<br>
<a href="/Samples.main">Back to Samples</a>