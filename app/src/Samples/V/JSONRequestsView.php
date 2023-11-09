<h2>JSON Requests samples</h2>

Same algorithm as GET and POST, data collected from json_decode(file_get_contents('php://input'), true)<br>
Send JSON request and see results. <br><br>

<form name="form1" method="post" action="javascript:void(0);" onsubmit="submitForm()">
   id <input type="text" id="id" value="123"><br>
   name <input type="text" id="name" value="Dmytro"><br>
    <input type="submit" name="Submit" value="Send JSON to see"><br>
</form>
<script>
    function submitForm() {
        var id = document.getElementById('id').value;
        var name = document.getElementById('name').value;

        var jsonData = {
            id: id,
            name: name
        };

        var jsonString = JSON.stringify(jsonData);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '?jsonDemo=yes', true);
        xhr.setRequestHeader('Content-Type', 'application/json');


        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                document.getElementById('resultId').textContent = response.id;
                document.getElementById('resultName').textContent = response.name;
            }
        };
        xhr.send(jsonString);
    }
</script>
<br><br>

Results:<br>
$request->JSON("id", "int") is:<span id="resultId"></span><br>
$request->JSON("name", "varchar") is:<span id="resultName"></span><br>