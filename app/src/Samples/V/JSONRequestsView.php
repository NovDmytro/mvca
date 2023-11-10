<section class="json-request">
    <h2 class="sample__header">JSON Requests samples</h2>
    <p class="sample__text sample__subheader">Provide two main functions - requestion and sanitize</p>
    <p class="sample__text sample__subheader">Fillup form and <span class="important"> 'Click'</span> the submit button to <span class="positive">JSON</span> behaviour test.</p>
    

    <p class="sample__text">MVCA JSON Request collected data via classic <span class="important">json_decode(file_get_contents(<span class="route">'php://input'</span>), true)</span> and requires to use browser <span class="positive">WEB API via JavaScript (fetch, XMLHttpRequest)</span>.</p>
    
    <div class="sample__container">
        <div class="sample-request__left">
            <form class="sample__form">
                <h3 class="sample__sec-header">Test form</h3>
                <label class="sample__label" for="id">id
                    <input class="sample__input" name='id' type="text" id="id" value="123fdgfdgd=);+1#%*">
                </label>
                <label class="sample__label" for="name">name 
                    <input class="sample__input" name="name" type="text" id="name" value="Dmytro=);+134#%*">
                </label>

                <button class="sample__link">SUBMIT</button>
            </form>
        </div>
        <div class="sample-request__right">
            <h3 class="sample__sec-header">Results</h3>
            <p class="sample__text"><span class="important">$request->JSON(<span class="route">"id"</span>, <span class="route">"int"</span>)</span> is: <span data-result="id"></span></p>
            <p class="sample__text"><span class="important">$request->JSON(<span class="route">"name"</span>, <span class="route">"varchar"</span>)</span> is: <span data-result="name"></span></p>
        </div>
    </div>

    <section class="subsection">
        <h3 class="sample__sec-header">request controller.php</h3>
        <p class="sample__text"> <span class="warning">Important </span> to init <span class="important">$request</span> singleton in method of page controller before use: <span class="important">$request = Request::init();</span>.</p>	      

        <p class="sample__text">Request and variables call pattern: <span class="important"> echo json_encode([<span class="route">'parameter'</span>=> $request->JSON(<span class="route">'parameter'</span>, <span class="route">'filter'</span>, <span class="route">'case'</span>)]);</span>. Second and third parameters of MVCA POST Request are optional and usefull for sanitizing.</p>

        <p class="sample__text"> Sanitize filters: <span class="route">'int, dec, hex, email, latin,varchar, html'</span> .  Case options: <span class="route">'low, up'</span>.</p>

        <p class="sample__text">Sample: <span class="important">if ($request->GET('jsonDemo') == 'yes'){echo json_encode(['id' => $request->JSON('id', 'int')]);}</span></p>
        
        <p class="sample__text">This filter does not validate entry data but simply cleans up potentialy danger characters.</p>

        <p class="sample__text">Source code at controller:<span class="route"> app/src/Samples/C/RequestsController.php</span></p>
    </section>

    <section class="subsection">
		<h3 class="sample__sec-header">sample view.php</h3>
		<p class="sample__text">MVCA JSON Requst inframework service wich react on conditions for exaple if pass <span class="important">$request->GET('jsonDemo') == 'yes'</span> as in sample upper.</p>
		
        <p class="sample__text">Expression <span class="important">$request->GET(<span class="route">'url'</span>)</span> use url at request via JavaScript. JavaScript fetch <span class="route">'url'</span> must be in form <span class="important">'?<span class="route">anyText</span>=<span class="route">conditionPAssingText</span>'</span>.</p>

		<p class="sample__text">Source code at view:<span class="route"> app/src/Samples/V/JSONRequestsView.php</span>. </p>
	</section>
	
	<p class="sample__text">You can use: 
		<a href="/Samples-Requests-getSamples">$request->GET</a>    
		<a href="/Samples-Requests-postSamples">$request->POST</a>
		<a href="/Samples-Requests-jsonSamples">$request->SERVER</a>
		<a href="/Samples-Requests-serverSamples">$request->JSON</a>
		$request->COOKIE
	</p>
    
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const resultContainers = document.querySelectorAll('[data-result]');
            const forms = [...document.querySelectorAll('form')];
            
            forms.forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const inputs = [...form.querySelectorAll('input')];
                    const url = '?jsonDemo=yes';
                    const data = {};
                    
                    inputs.forEach(input => {
                        data[input.name] = input.value;
                    });

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();              
                    resultContainers.forEach(container => {
                        container.textContent = result[container.dataset.result]; 
                    })
                })
            })
        })
    </script>   
</section>


