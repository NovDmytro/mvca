This is basics view sample <br><br>
We had $view['someString']='Some string data'; in controller, lets view this from echo $someString;<br>
Here is it: <?= $someString ?><br><br>

Now lets see $view['modelExample']=$example; with var_dump($modelExample);<br>
Here is it: <?php var_dump($modelExample); ?><br><br>

Let's translate something <br>
(there are CURLANG in double curly braces, that will translate it in current language name): {{CURLANG}}<br><br>


Please visit:<br>
src/Samples/C/BasicsController.php<br>
src/Samples/M/BasicsModel.php<br>
src/Samples/V/BasicsView.php<br>

To understand how everything works