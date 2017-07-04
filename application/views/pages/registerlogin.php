<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link rel="icon" href="http://www.timecords.com/images/icon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://www.timecords.com/styles/style.css">
<style type="text/css">
#pagetopindex{
	background: #3e93ff;
	color: white;
	font-size: 20px;
	color: #ffffff;
	text-align: center;
	padding: 0px;
	width: 100%;
	height: 70px;
	padding-top: 15px;
}
#loginform{
	margin-top:24px;	
}
#loginform > div {
	margin-top: 12px;	
}
#loginform > input {
	width: 200px;
	padding: 3px;
	background: #FAFAFA;
}
#loginbtn {
	font-size:15px;
	padding: 10px;
}
#signupform{
	margin-top:24px;	
}
#signupform > div {
	margin-top: 12px;	
}
#signupform > input,select {
	width: 200px;
	padding: 3px;
	background: #FAFAFA;
}
#signupbtn {
	font-size:18px;
	padding: 12px;
}
#terms {
	border:#CCC 1px solid;
	background: #F5F5F5;
	padding: 12px;
}
#whole{
	position: relative;
	top: 80px;
	margin: auto;
	width: 300px;
	height: 480px;
}
#login_wrap{
	position: absolute;
	width: 300px;
	height: 430px;
	bottom: 0px;
	left: 0px;
	background-color: #E7F1FF;
	visibility: visible;
	text-align: center;
	z-index: 3;
}
#signup_wrap{
	position: absolute;
	width: 300px;
	height: 430px;
	bottom: 0px;
	left: 0px;
	background-color: #E7F1FF;
	visibility: hidden;
	text-align: center;
	z-index: 1;
}
#login_title{
	position: absolute;
	width: 150px;
	height: 50px;
	top: 0px;
	left: 0px;
	background-color: #E7F1FF;
	text-align: center;
	z-index: 4;
	border-top-left-radius: 1em;
	border-top-right-radius: 1em;
}
#signup_title{
	position: absolute;
	width: 150px;
	height: 50px;
	top: 0px;
	right: 0px;
	background-color: #c5deff;
	text-align: center;
	z-index: 2;
	border-top-left-radius: 1em;
	border-top-right-radius: 1em;
}
.real_title{
	margin-top: 15px;
}
</style>
<script src="js/main.js"></script>
<script src="js/ajax.js"></script>
<script>
function login(){
	var e_login = _("email_login").value;
	var p_login = _("password_login").value;
	if(e_login == "" || p_login == ""){
		_("status_login").innerHTML = "Fill out all of the form data";
	} else {
		_("loginbtn").style.display = "none";
		_("status_login").innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "http://www.timecords.com/registerlogin/login");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	        	_("status_login").innerHTML = ajax.responseText;
	            if(ajax.responseText == "login_failed"){
					_("status_login").innerHTML = "Login unsuccessful, please try again.";
					_("loginbtn").style.display = "block";
					_("loginbtn").style.margin = "auto";
				} else {
					window.location = "http://www.timecords.com/user/"+ajax.responseText;
				}
	        }
        }
        ajax.send("e_login="+e_login+"&p_login="+p_login);
	}
}
function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
function emptyElement(x){
	_(x).innerHTML = "";
}
function checkusername(){
	var u = _("username").value;
	if(u != ""){
		_("unamestatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "http://www.timecords.com/registerlogin/check_username");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("unamestatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("usernamecheck="+u);
	}
}
function signup(){
	var u = _("username").value;
	var e = _("email").value;
	var p1 = _("pass1").value;
	var p2 = _("pass2").value;
	var g = _("gender").value;
	var status = _("status");
	if(u == "" || e == "" || p1 == "" || p2 == "" || g == ""){
		status.innerHTML = "Fill out all of the form data";
	} else if(p1 != p2){
		status.innerHTML = "Your password fields do not match";
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "http://www.timecords.com/registerlogin/register");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
					status.innerHTML = ajax.responseText;
					_("signupbtn").style.display = "block";
					_("signupbtn").style.margin = "auto";
				} else {
					window.scrollTo(0,0);
					_("signupform").innerHTML = "Please check your email inbox and junk mail box at <u>"+e+"</u> to activate your account.";
				}
	        }
        }
        ajax.send("u="+u+"&e="+e+"&p="+p1+"&g="+g);
	}
}
function front(w,x,y,z){
	_(w).style.backgroundColor= "#E7F1FF";
	_(x).style.visibility= "visible";
	_(y).style.backgroundColor= "#c5deff";
	_(z).style.visibility= "hidden";
	_(w).style.zIndex= "4";
	_(x).style.zIndex= "3";
	_(y).style.zIndex= "2";
	_(z).style.zIndex= "1";
}
function addEvents(){
	_("username").addEventListener("keyup", function(){
    	restrict("username");
	});
	_("username").addEventListener("blur", checkusername);
}
window.onload = addEvents;
</script>
</head>
<body>
<div id="pagetopindex">
	<div> TimeCords </div>
</div>
<div id="pageMiddle">
<div id="whole">
  <div id="login_title" onclick="front('login_title', 'login_wrap', 'signup_title', 'signup_wrap')"><div class="real_title"><b>Log In</b></div></div>
  <div id="login_wrap">
  <!-- LOGIN FORM -->
  <form id="loginform" onsubmit="return false;">
    <div>Email Address:</div>
    <input type="text" id="email_login" onfocus="emptyElement('status_login')" maxlength="88">
    <div>Password:</div>
    <input type="password" id="password_login" onfocus="emptyElement('status_login')" maxlength="100">
    <br /><br />
    <button id="loginbtn" onclick="login()">Log In</button> 
    <p id="status_login"></p>
    <!-- <a href="forgot_pass.php">Forgot Your Password?</a> -->
  </form>
  </div>
  <!-- LOGIN FORM -->
  <div id="signup_title" onclick="front('signup_title', 'signup_wrap', 'login_title', 'login_wrap')"><div class="real_title"><b>Sign Up</b></div></div>
  <div id="signup_wrap">
  <form name="signupform" id="signupform" onsubmit="return false;">
    <div>Username: </div>
    <input id="username" type="text" maxlength="16">
    <p id="unamestatus"></p>
    <div>Email Address:</div>
    <input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
    <div>Create Password:</div>
    <input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="16">
    <div>Confirm Password:</div>
    <input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="16">
    <div>Gender:</div>
    <select id="gender" onfocus="emptyElement('status')">
      <option value=""></option>
      <option value="m">Male</option>
      <option value="f">Female</option>
    </select>
    <br /><br />
    <button id="signupbtn" onclick="signup()">Create Account</button>
    <p id="status"></p>
  </form>
  </div>
</div>
</div>
<?php
	$this->load->view('templates/template_pageBottom');
?>
</body>
</html>