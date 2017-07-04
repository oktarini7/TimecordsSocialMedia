<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notifications and Friend Requests</title>
<link rel="icon" href="http://www.timecords.com/images/icon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://www.timecords.com/styles/style.css">
<style type="text/css">
div#notesBox{float:left; width:430px; background:#E7F1FF; margin-right:60px; padding:10px;}
div#friendReqBox{float:right; background:#E7F1FF; width:430px; padding:10px;}
div.friendrequests{height:74px; border-bottom:#CCC 1px solid; margin-bottom:8px;}
img.user_pic{float:left; width:68px; height:68px; margin-right:8px;}
div.user_info{float:left; font-size:14px;}
</style>
<script src="http://www.timecords.com/js/main.js"></script>
<script src="http://www.timecords.com/js/ajax.js"></script>
<script type="text/javascript">
function friendReqHandler(action,reqid,user1,elem){
	var conf = confirm("Press OK to '"+action+"' this friend request.");
	if(conf != true){
		return false;
	}
	_(elem).innerHTML = "processing ...";
	var ajax = ajaxObj("POST", "http://www.timecords.com/friendsystem/friendrequest");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "accept_ok"){
				_(elem).innerHTML = "<b>Request Accepted!</b><br />Your are now friends";
			} else if(ajax.responseText == "reject_ok"){
				_(elem).innerHTML = "<b>Request Rejected</b><br />You chose to reject friendship with this user";
			} else {
				_(elem).innerHTML = ajax.responseText;
			}
		}
	}
	ajax.send("action="+action+"&reqid="+reqid+"&user1="+user1);
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
  <!-- START Page Content -->
  <div id="notesBox"><h2>Notifications</h2><?php echo $notification_list; ?></div>
  <div id="friendReqBox"><h2>Friend Requests</h2><?php echo $friend_requests; ?></div>
  <div style="clear:left;"></div>
  <!-- END Page Content -->
</div>
<?php
	$this->load->view('templates/template_pageBottom');
?>
</body>
</html>