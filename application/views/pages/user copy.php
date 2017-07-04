<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $otherProfile['username']; ?></title>
<link rel="icon" href="http://timecords.com/images/icon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://www.timecords.com/styles/style.css">
<style type="text/css">
div#profile_pic_box{border:#999 2px solid; width:200px; height:200px; margin:20px 30px 0px 0px; overflow-y:hidden;}
div#profile_pic_box > img{z-index:2000; width:200px;}
div#profile_pic_box > a {
	display: none;
	position:absolute; 
	margin:140px 0px 0px 120px;
	z-index:4000;
	background:#E7F1FF;
	border:#3e93ff 1px solid;
	border-radius:3px;
	padding:5px;
	font-size:12px;
	text-decoration:none;
	color:#60750B;
}
div#profile_pic_box > form{
	display:none;
	position:absolute; 
	z-index:3000;
	padding:10px;
	opacity:.8;
	background:#E7F1FF;
	width:180px;
	height:180px;
}
div#profile_pic_box:hover a {
    display: block;
}
</style>
<style type="text/css">
textarea#statustext{width:982px; height:80px; padding:8px; border:#3e93ff 1px solid; font-size:16px;}
div.status_boxes{padding:0; line-height:1.5em; margin-top: 10px;}
div.status_boxes > div{padding:8px; border:#3e93ff 1px solid; background: #E7F1FF;}
div.status_boxes > div > b{font-size:12px;}
div.status_boxes > button{padding:5px; font-size:12px;}
textarea.replytext{width:976px; height:40px; padding:8px; border:#3e93ff 1px solid; border-left: #3e93ff 7px solid;}
div.status_boxes > div.reply_boxes{padding:12px; background:#E7F1FF; border:#3e93ff 1px solid; border-left: #3e93ff 7px solid;}
div.status_boxes > div.reply_boxes > b{font-size:12px;}
</style>
<script src="http://timecords.com/js/main.js"></script>
<script src="http://timecords.com/js/ajax.js"></script>
<script type="text/javascript">
function friendToggle(type,user,elem){
	var conf = confirm("Press OK to confirm the '"+type+"' action for user <?php echo $otherProfile['username']; ?>.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = 'please wait ...';
	var ajax = ajaxObj("POST", "http://www.timecords.com/friendsystem/action");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent"){
				_(elem).innerHTML = 'OK Friend Request Sent';
			} else if(ajax.responseText == "unfriend_ok"){
				_(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $otherProfile['username']; ?>\',\'friendBtn\')">Request As Friend</button>';
			} else {
				alert(ajax.responseText);
				_(elem).innerHTML = 'Try again later';
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}
</script>
<script>
function postToStatus(action,type,user,ta){
	var data = _(ta).value;
	if(data == ""){
		alert("The field is empty");
		return false;
	}
	_("statusBtn").disabled = true;
	var ajax = ajaxObj("POST", "http://www.timecords.com/posttostatus");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "post_ok"){
				var sid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				var currentHTML = _("statusarea").innerHTML;
				_("statusarea").innerHTML = '<div id="status_'+sid+'" class="status_boxes"><div><b>Posted by you just now:</b> <span id="sdb_'+sid+'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''+sid+'\',\'status_'+sid+'\');" title="DELETE THIS STATUS AND ITS REPLIES">delete status</a></span><br />'+data+'</div></div><textarea id="replytext_'+sid+'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'+sid+'" onclick="replyToStatus('+sid+',\'<?php echo $otherProfile['username']; ?>\',\'replytext_'+sid+'\',this)">Reply</button>'+currentHTML;
				_("statusBtn").disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("type="+type+"&user="+user+"&data="+data);
}
function replyToStatus(sid,user,ta,btn){
	var data = _(ta).value;
	if(data == ""){
		alert("The field is empty");
		return false;
	}
	_("replyBtn_"+sid).disabled = true;
	var ajax = ajaxObj("POST", "http://www.timecords.com/replytostatus");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "reply_ok"){
				var rid = datArray[1];
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				_("status_"+sid).innerHTML += '<div id="reply_'+rid+'" class="reply_boxes"><div><b>Reply by you just now:</b><span id="srdb_'+rid+'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''+rid+'\',\'reply_'+rid+'\');" title="DELETE THIS COMMENT">remove</a></span><br />'+data+'</div></div>';
				_("replyBtn_"+sid).disabled = false;
				_(ta).value = "";
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("sid="+sid+"&user="+user+"&data="+data);
}
function deleteStatus(statusid,statusbox){
	var conf = confirm("Press OK to confirm deletion of this status and its replies");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "http://www.timecords.com/deletestatus");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(statusbox).style.display = 'none';
				_("replytext_"+statusid).style.display = 'none';
				_("replyBtn_"+statusid).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("statusid="+statusid);
}
function deleteReply(replyid,replybox){
	var conf = confirm("Press OK to confirm deletion of this reply");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "http://www.timecords.com/deletereply");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "delete_ok"){
				_(replybox).style.display = 'none';
			} else {
				alert(ajax.responseText);
			}
		}
	}
	ajax.send("replyid="+replyid);
}
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+" maximum character limit reached");
		field.value = field.value.substring(0, maxlimit);
	}
}
</script>
</head>
<body>
<?php
	if ($isOwner){
		$whose= "My";
	} else {
		$whose= $otherProfile['username'] . "'s";
	}
	$data['otherProfile']= $otherProfile;
	$data['loggedUser']= $loggedUser;
	$data['whose']= $whose;
	$this->load->view('templates/template_pageTop', $data);
?>
<div id="pageMiddle">
  <h3><?php echo $otherProfile['username']; ?></h3>
  <p><span id="friendBtn"><?php echo $friendButton; ?></span></p>
  <div id="profile_pic_box"><?php echo $profile_pic_btn; ?><?php echo $avatar_form; ?><?php echo $profile_pic; ?></div>
  <p>Gender: <?php echo $otherProfile['gender']; ?></p>
  <hr />
  <div id="statusui">
  	<?php echo $status_ui; ?>
  </div>
  <div id="statusarea">
  	<?php echo $statuslist; ?>
  </div>
  <div id="error">
  </div>
</div>
<?php
	$this->load->view('templates/template_pageBottom');
?>
</body>
</html>