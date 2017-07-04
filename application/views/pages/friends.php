<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $a; ?> friends</title>
<link rel="icon" href="http://www.timecords.com/images/icon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://www.timecords.com/styles/style.css">
<style type="text/css">
img.friendpics{border:#000 1px solid; width:40px; height:40px; margin:2px;}
</style>
<script src="http://www.timecords.com/js/main.js"></script>
<script src="http://www.timecords.com/js/ajax.js"></script>
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
  <p><?php echo $friendsTitle ." ". $friend_count." friend(s)"; ?></p>
  <hr />
  <p><?php echo $friendsHTML; ?></p>
</div>
<?php
	$this->load->view('templates/template_pageBottom');
?>
</body>
</html>