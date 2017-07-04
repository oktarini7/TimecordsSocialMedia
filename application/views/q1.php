<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Redcomm Q1</title>
<script src="http://www.timecords.com/js/ajax.js"></script>
<script>
function generate(){
	var q1input = document.getElementById("q1input").value;
	if(q1input == ""){
		document.getElementById("output").innerHTML = "Please fill in the input value";
	} else {
		document.getElementById("q1button").style.display = "none";
		document.getElementById("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "http://www.timecords.com/processq1");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	        	document.getElementById("status").innerHTML = ajax.responseText;
	            document.getElementById("q1button").style.display = "block";
	        }
        }
        ajax.send("q1input="+q1input);
	}
}
</script>
</head>
<body>
  <form name="q1_form" id="q1_form" onsubmit="return false;">
    <div>Input: </div>
    <input id="q1input" type="number"><br />
    <button id="q1button" onclick="generate()">Generate</button>
    <p id="status"></p>
  </form>
</body>
</html>