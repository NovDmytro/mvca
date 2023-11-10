<section class="get-request">
	<h2 class="sample__header">GET Requests samples</h2>
	<p class="sample__text sample__subheader">Provide two main functions - requestion and sanitize</p>

	<div class="sample__container">
		<div class="sample-request__left">
			<p class="sample__text"><a class="important" href="<?= $getDemoURL ?>">"Click"</a> the request button to <span class="positive">GET</span> behaviour test.</p>
			
			<p class="sample__text">Test route: <span class="route"><?= $getDemoURL ?></span> </p>
			
			<p class="sample__text">First dir <span class="positive">'route' </span>reserved for routing. Rest six dirs <span class="positive">'var1-6'</span> is under your control. Put there whatever you want.</p>
			
			<p class="sample__text">Routes and dirs customization at <span class="route">app/public/.htaccess</span>. You able to modify amount of available dirs and names of variables such as <span class="positive">'route' </span> and <span class="positive">'var1-6'</span>. <span class="warning">Beware</span> to change names in ongoing project: cascade changes at all pages need.</p>			
		</div>
		<div class="sample-request__right">
			<p class="sample__text"> <span class="positive">'route'</span>  is: <span class="route"><?= $dirs['route'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'var1'</span>  is: <span class="route"><?= $dirs['var1'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'var2'</span>  is: <span class="route"><?= $dirs['var2'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'var3'</span>  is: <span class="route"><?= $dirs['var3'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'var4'</span>  is: <span class="route"><?= $dirs['var4'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'var5'</span>  is: <span class="route"><?= $dirs['var5'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'var6'</span>  is: <span class="route"><?= $dirs['var6'] ?></span>  </p>
			<p class="sample__text"> <span class="positive">'querySample1'</span>  is: <span class="route"><?= $query['querySample1'] ?></span> </p>
			<p class="sample__text"> <span class="positive">'querySample2'</span>  is: <span class="route"><?= $query['querySample2'] ?></span> </p>
			<p class="sample__text"> <span class="positive">'intFilterSample'</span> ,<span class="positive">'int'</span> is: <span class="route"><?= $query['intFilterSample'] ?></span> </p>
			<a class="sample__link" href="<?= $getDemoURL ?>">request button</a>
		</div>
	</div>
	
	<section class="subsection">
		<h3 class="sample__sec-header">request controller.php</h3>
		<p class="sample__text"> <span class="warning">Important </span> to init <span class="important">$request</span> singleton in method of page controller before use: <span class="important">$request = Request::init();</span>.</p>	
		
		<p class="sample__text">Request and variables creation pattern: <span class="important">$view[<span class="route">'dirs'</span>][<span class="route">'dirName'</span>]= $request->GET(<span class="route">'dirName'</span>, <span class="route">'filter'</span>, <span class="route">'case'</span>)</span>. </p>

		<p class="sample__text">Second and third parameters of MVCA Get Request are optional and usefull for processing the 'queryParameters'. Do not use it for dirs.</p>
		
		<p class="sample__text"> Sanitize filters: <span class="route">'int, dec, hex, email, latin,varchar, html'</span> .  Case options: <span class="route">'low, up'</span>.</p>
		
		<p class="sample__text">Samples: <span class="important">$request->GET('example','email','low');</span> and <span class="important">$request->GET('id','int');</span></p>

		<p class="sample__text">This filter does not validate entry data but simply cleans up potentialy danger characters.</p>

		<p class="sample__text">Source code at controller:<span class="route"> app/src/Samples/C/RequestsController.php</span></p>
	</section>
	
	<section class="subsection">
		<h3 class="sample__sec-header">sample view.php</h3>
		<p class="sample__text">Accesion to variable pattern: <span class="important">&lt;?= $dirs['dirName'] ?&gt;</span> </p>
		<p class="sample__text">Aviability provide array <span class="important">$view['dirs']['dirName']</span>. Parent's <span class="important">['dirs']['dirName']</span> transform into <span class="important">$dirs['dirName']</span>  </p>
		<p class="sample__text">Source code at view:<span class="route"> app/src/Samples/V/GETRequestsView.php</span></p>
	</section>
	
	<p class="sample__text">You can use: 
		<a href="/Samples-Requests-getSamples">$request->GET</a>    
		<a href="/Samples-Requests-postSamples">$request->POST</a>
		<a href="/Samples-Requests-jsonSamples">$request->SERVER</a>
		<a href="/Samples-Requests-serverSamples">$request->JSON</a>
		$request->COOKIE
	</p>	
</section>
