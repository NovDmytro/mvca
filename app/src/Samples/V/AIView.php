<?php
/**
 * @var string $someString
 * @var array $modelExample
 */
?>
<h2>AI through WebSocket MVC sample</h2>
To run demo worker for click this and back here & refresh page
<a href="/Samples.AI.worker/" target="_blank"> Run worker: /Samples.AI.worker/</a>


<pre>
    <div id="container"></div>
	</pre>


<form id="formId">
    <input type="hidden" id="name" placeholder="name" value="Client">
    <input
            type="text"
            id="sendData"
            placeholder="data to send"
            style="width:100%; max-width:1800px; padding:8px 12px; box-sizing:border-box;"
    >    <button type="submit">Send</button>
</form>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        var websocket = new WebSocket('ws://127.0.0.1:8081/Samples-AI-worker');

        websocket.onopen = function (event) {
            showMessage("CONN 220 Connection is established!\n");
        };

        websocket.onmessage = function (event) {
            var Data = JSON.parse(event.data);
            showMessage(Data.type + " " + Data.data + "\n");
            document.getElementById('sendData').value = '';
        };

        websocket.onerror = function (event) {
            showMessage("CONN 500 Problem due to some Error\n");
        };

        websocket.onclose = function (event) {
            showMessage("CONN 421 Connection Closed\n");
        };

        function showMessage(messageHTML) {
            document.getElementById('container').insertAdjacentHTML('beforeend', messageHTML);
        }

        document.getElementById('formId').addEventListener('submit', function (event) {
            event.preventDefault();
            document.getElementById('name').setAttribute('type', 'hidden');
            var messageJSON = {
                name: document.getElementById('name').value,
                data: document.getElementById('sendData').value,
            };
            websocket.send(JSON.stringify(messageJSON));
        });
    });
</script>