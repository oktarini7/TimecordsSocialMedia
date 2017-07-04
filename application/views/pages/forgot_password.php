<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header them away
if($user_ok == true){
	header("location: user.php?u=".$_SESSION["username"]);
    exit();
}
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["e"])){
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$sql = "SELECT id, username FROM users WHERE email='$e' AND activated='1' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$u = $row["username"];
		}
		$emailcut = substr($e, 0, 4);
		$randNum = rand(10000,99999);
		$tempPass = "$emailcut$randNum";
		$hashTempPass = md5($tempPass);
		$sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$to = "$e";
		$from = "auto_responder@timecords.com";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject ="Timecords Temporary Password";
		$msg = '<h2>Hello '.$u.'</h2><p>This is an automated message from Timecords.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p><p><a href="http://www.timecords.com/forgot_pass.php?u='.$u.'&p='.$hashTempPass.'">Click here now to apply the temporary password shown below to your account</a></p>';
		if(mail($to,$subject,$msg,$headers)) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
    exit();
}
?><?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
	if(strlen($temppasshash) < 10){
		exit();
	}
	$sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows == 0){
		header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
    	exit();
	} else {
		$row = mysqli_fetch_row($query);
		$id = $row[0];
		$sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
	    header("location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="icon" href="images/icon.ico" type="image/x-icon">
<link rel="stylesheet" href="styles/style.css">
<style type="text/css">
#forgotpassform{
	margin-top:24px;	
}
#forgotpassform > div {
	margin-top: 12px;	
}
#forgotpassform > input {
	width: 250px;
	padding: 3px;
	background: #F3F9DD;
}
#forgotpassbtn {
	font-size:15px;
	padding: 10px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function forgotpass(){
	var e = _("email").value;
	if(e == ""){
		_("status").innerHTML = "Type in your email address";
	} else {
		_("forgotpassbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "forgot_pass.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("forgotpassform").innerHTML = '<h3>Step 2. Check your email inbox in a few minutes</h3><p>You can close this window or tab if you like.</p>';
				} else if (response == "no_exist"){
					_("status").innerHTML = "Sorry that email address is not in our system";
				} else if(response == "email_send_failed"){
					_("status").innerHTML = "Mail function failed to execute";
				} else {
					_("status").innerHTML = "An unknown error occurred";
				}
	        }
        }
        ajax.send("e="+e);
	}
}
</script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <h3>Generate a temorary log in password</h3>
  <form id="forgotpassform" onsubmit="return false;">
    <div>Step 1: Enter Your Email Address</div>
    <input id="email" type="text" onfocus="_('status').innerHTML='';" maxlength="88">
    <br /><br />
    <button id="forgotpassbtn" onclick="forgotpass()">Generate Temporary Log In Password</button> 
    <p id="status"></p>
  </form>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>