<?php
/**
 * @var array $postSample
 */
?>
<h2>JSON Request samples</h2>
<p>//To understand how Request work please fill up and submit demo JSON request:</p>
<form>
    <label>id: <input name='id' type="text" id="id" value="123MVCAmvca=);+1#%*"></label><br>
    <label>name: <input name="name" type="text" id="name" value="Dmytro=);+134#%*"></label><br>
    <button>Submit</button><br>
</form>
<p>//If you submitted previous form now you can see this data:</p>
<p>$request->JSON('id', 'int'): <span data-result="id"></span></p>
<p>$request->JSON('name', 'varchar'): <span data-result="name"></span></p>
<br>
<a href="/Samples.main">Back to Samples</a>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const resultContainers = document.querySelectorAll('[data-result]');
            const forms = [...document.querySelectorAll('form')];

            forms.forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const inputs = [...form.querySelectorAll('input')];
                    const url = '/Samples.Request.requestJsonData/';
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