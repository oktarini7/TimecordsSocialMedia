<?php
include_once("php_includes/check_login_status.php");
// If user is not logged in, header them away
if($user_ok != true){
	header("location: http://www.timecords.com");
    exit();
}
?><?php
$u= $log_username;
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["op"]) && isset($_POST["np"]) && isset($_POST["op"])){
	$sql = "SELECT password FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows == 0){
		header("location: message.php?msg=User is not on database.");
    	exit();
	} else {
		$op= md5($_POST["op"]);
		$np= $_POST["np"];
		$cnp= $_POST["cnp"];
		$row = mysqli_fetch_row($query);
		$db_password = $row[0];
		if($db_password != $op){
			echo "wrong_pass";
			exit();
		}
		if ($np != $cnp){
			echo "pass_diff";
			exit();
		} else {
			$np= md5($np);
			$sql = "UPDATE users SET password='$np' WHERE username='$log_username' AND activated='1' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			if(mysqli_query($db_conx, $sql)){
				$_SESSION['password'] = $np;
				$_COOKIE['pass'] = $np;
				echo "success";
				exit();
			} else{
				echo "update_failed";
				exit();
			}
		}
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
#changepass{
	margin-top:24px;	
}
#changepass > div {
	margin-top: 12px;	
}
#changepass > input {
	width: 250px;
	padding: 3px;
	background: #FAFAFA;
}
#changepassbtn {
	font-size:15px;
	padding: 10px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function changepassfn(){
	var op = _("oldpass").value;
	var np = _("newpass").value;
	var cnp = _("cnewpass").value;
	if(op == "" || np == "" || cnp == ""){
		_("status").innerHTML = "Please fill in the required value";
	} else {
		_("changepassbtn").style.display = "none";
		_("status").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "change_pass.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					_("status").innerHTML = "Your password now has been changed.";
				} else if (response == "wrong_pass"){
					_("status").innerHTML = "The password you entered does not match with our database.";
				} else if(response == "pass_diff"){
					_("status").innerHTML = "The new password and confirm new password are different.";
				} else {
					_("status").innerHTML = response;
				}
				_("changepassbtn").style.display = "block";
	        }
        }
        ajax.send("op="+op+"&np="+np+"&cnp="+cnp);
	}
}
</script>
</head>
<body>
<?php include_once("template_pageTop.php"); ?>
<div id="pageMiddle">
  <h3>Change your password</h3>
  <form id="changepass" onsubmit="return false;">
    <div>Password:</div>
    <input id="oldpass" type="password" onfocus="_('status').innerHTML='';" maxlength="88">
    <br />
    <div>New Password:</div>
    <input id="newpass" type="password" onfocus="_('status').innerHTML='';" maxlength="88">
    <br />
    <div>Confirm New Password:</div>
    <input id="cnewpass" type="password" onfocus="_('status').innerHTML='';" maxlength="88">
    <br /><br />
    <button id="changepassbtn" onclick="changepassfn()">Change Password</button> 
    <p id="status"></p>
  </form>
</div>
<?php include_once("template_pageBottom.php"); ?>
</body>
</html>