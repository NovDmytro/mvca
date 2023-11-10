<section class="post-request">
    <h2 class="sample__header">POST Requests samples</h2>
    <p class="sample__text sample__subheader">Provide two main functions - request and sanitize</p>
    <p class="sample__text sample__subheader">Fill up form and <span class="important"> 'Click'</span> the submit button to <span class="positive">POST</span> behaviour test.</p>
    <div class="sample__container">
        <div class="sample-request__left">
            <form class="sample__form" method="post">
                <h3 class="sample__sec-header">Test form</h3>
                <label class="sample__label" for="sample-upper">Transform to upper case
                    <input class="sample__input" id="sample-upper" type="text" name="upCaseSample" value="FooBar=);+1#%*">
                </label>
                <label class="sample__label" for="sample-email">Email transform
                    <input class="sample__input" id="sample-email" type="text" name="emailSample" value="email@example.com=);+1#%*">
                </label>
                <label class="sample__label" for="sample-filter">Leave integers 
                    <input class="sample__input" id="sample-filter" type="text" name="filteredNumbersSample" value="-1 2+3 AbC =);+1#%*">
                </label>
                
                <button class="sample__link">SUBMIT</button>
            </form>           
        </div>
        <div class="sample-request__right">
            <h3 class="sample__sec-header">Results</h3>
            <p class="sample__text"><span class="important">$request->POST(<span class="route">'upCaseSample'</span>,<span class="route">'varchar'</span>,<span class="route">'up'</span>)</span> is: <span class="route"><?= $postSample['upCaseSample'] ?></span></p>
            <p class="sample__text"><span class="important">$request->POST(<span class="route">'emailSample'</span>,<span class="route">'email'</span>,<span class="route">'low'</span>)</span> is: <span class="route"><?= $postSample['emailSample'] ?></span></p>
            <p class="sample__text"><span class="important">$request->POST(<span class="route">'filteredNumbersSample'</span>,<span class="route">'int'</span>,<span class="route">'low'</span>)</span> is: <span class="route"><?= $postSample['filteredNumbersSample'] ?></span></p>
            <p></p>
        </div>
    </div>

    <section class="subsection">
        <h3 class="sample__sec-header">request controller.php</h3>
        <p class="sample__text"> <span class="warning">Important </span> to init <span class="important">$request</span> singleton in method of page controller before use: <span class="important">$request = Request::init();</span>.</p>	

        <p class="sample__text">Request and variables call pattern: <span class="important">$view[<span class="route">'postSample'</span>][<span class="route">'upCaseSample'</span>]= $request->POST(<span class="route">'SampleName'</span>, <span class="route">'filter'</span>, <span class="route">'case'</span>)</span>. Second and third parameters of MVCA POST Request are optional and usefull for sanitizing.</p>

        <p class="sample__text"> Sanitize filters(optional): <span class="route">'int, dec, hex, email, latin,varchar, html'</span> .  Case options(optional): <span class="route">'low, up'</span>.</p>

        <p class="sample__text">Sample: <span class="important">$request->POST('example','email','low');</span></p>
        
        <p class="sample__text">This filter does not validate entry data but simply cleans up potentially danger characters.</p>

        <p class="sample__text">Source code at controller:<span class="route"> app/src/Samples/C/RequestsController.php</span></p>
    </section>

    <section class="subsection">
		<h3 class="sample__sec-header">sample view.php</h3>
		<p class="sample__text">Accession to variable pattern: <span class="important">&lt;?= $postSample['upCaseSample'] ?&gt;</span> </p>
		<p class="sample__text">Availability provide array <span class="important">$view['postSample']['upCaseSample']</span>. Parent's <span class="important">['postSample']['upCaseSample']</span> transform into <span class="important">$postSample['upCaseSample']</span>  </p>
		<p class="sample__text">Source code at view:<span class="route"> app/src/Samples/V/PostRequestsView.php</span></p>
	</section>
	
	<p class="sample__text">You can use: 
		<a href="/Samples-Requests-getSamples">$request->GET</a>    
		<a href="/Samples-Requests-postSamples">$request->POST</a>
		<a href="/Samples-Requests-jsonSamples">$request->SERVER</a>
		<a href="/Samples-Requests-serverSamples">$request->JSON</a>
		$request->COOKIE
	</p>
</section>
