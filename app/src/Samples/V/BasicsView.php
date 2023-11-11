<section class="basic">
    <h2 class="sample__header">Basic MVCA sample</h2>
    <p class="sample__text sample__subheader">This is basics view sample</p>

    <p class="sample__text"> We had <span class="important">$view[<span class="route">'someString'</span>]=<span class="route">'Some string data'</span>;</span>  in controller, lets view this from echo <span class="route">$someString;</span> </p>
    <p class="sample__text"> Here is it: <span class="important">&lt;?= <span class="route">$someString </span> ?&gt;</span> => <span class="route"><?= $someString ?></span></p>
    <p class="sample__text"> Structure of variable <span class="important">$view[<span class="route">'modelExample'</span>]=$example;</span> <span class="important">ith var_dump($modelExample);</span>:</p>

    <div class="pre-styling">
        <?php var_dump($modelExample); ?>
    </div>

    <p class="sample__text">Let's get lang current lang version something via syntax <span class="route">&#123;&#123;CURLANG&#125;&#125;</span> => <span class="route">{{CURLANG}}</span></p>
    <p class="sample__text">So CURLANG in double curly braces contain current language.</p>
    <p class="sample__text">Source code at:</p>
    <ul class="basic__ul">
        <li><p class="sample__text"><span class="route">src/Samples/C/BasicsController.php</span></p></li>
        <li><p class="sample__text"><span class="route">src/Samples/M/BasicsModel.php</span></p></li>
        <li><p class="sample__text"><span class="route">src/Samples/V/BasicsView.php</span></p></li>
    </ul>
</section>

<span class="important"></span>
<span class="warning"></span>
<span class="route"></span>

