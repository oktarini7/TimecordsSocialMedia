<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Guests Book</title>
<script src="http://www.timecords.com/js/ajax.js"></script>
<script>
function submit(){
	var name = document.getElementById("name").value;
	var address = document.getElementById("address").value;
	var phone = document.getElementById("phone").value;
	var note = document.getElementById("note").value;
	if(name == "" || address == "" || phone == "" || note == ""){
		document.getElementById("status").innerHTML = "Fill out all input";
	} else {
		document.getElementById("button").style.display = "none";
		document.getElementById("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "indosystem/submitNote");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	        	document.getElementById("status").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("name="+name+"address="+address+"phone="+phone+"note="+note);
	}
}
</script>
</head>
<body>
  <?php echo $a; ?>
  <form id="loginform" onsubmit="return false;">
    <div>Name:</div>
    <input type="text" id="name">
    <div>Address:</div>
    <input type="text" id="address">
     <div>Phone:</div>
    <input type="text" id="phone">
 	<div>Note:</div>
    <input type="textarea" id="note">
    <br /><br />
    <button id="submit" onclick="submit()">submit</button> 
  </form>
  <p id="status"> </p>
  <?php var_dump($allNotes); ?>
</body>
</html>