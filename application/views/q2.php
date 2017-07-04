<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Redcomm Q2</title>
<script src="http://www.timecords.com/js/ajax.js"></script>
<script>
function generate(){
	var T = document.getElementById("T").value;
	var q2input = document.getElementById("q2input").value;
	if( T == "" || q2input == ""){
		document.getElementById("status").innerHTML = "Please fill in the input value";
	} else {
		document.getElementById("q2button").style.display = "none";
		document.getElementById("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "http://www.timecords.com/processq2");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	        	document.getElementById("status").innerHTML = ajax.responseText;
	            document.getElementById("q2button").style.display = "block";
	        }
        }
        ajax.send("T="+T+"&q2input="+q2input);
	}
}
</script>
</head>
<body>
  <form name="q2_form" id="q2_form" onsubmit="return false;">
    <div>Number of test cases T: </div>
    <input id="T" type="number">
    <div>Test cases separated by comma</div>
    <div>ex: aabb,abab,abba </div><br />
    ​<textarea id="q2input" rows="10" cols="70"></textarea>
    <br />
    <button id="q2button" onclick="generate()">Generate</button>
    <p id="status"></p>
  </form>
</body>
</html>