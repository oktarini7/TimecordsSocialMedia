<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $a; ?> Photos</title>
<link rel="icon" href="http://www.timecords.com/images/icon.ico" type="image/x-icon">
<link rel="stylesheet" href="http://www.timecords.com/styles/style.css">
<style type="text/css">
form#photo_form{background:#E7F1FF; padding:20px;}
div#galleries{}
div#galleries > div{float:left; margin:20px; text-align:center; cursor:pointer;}
div#galleries > div > div {height:100px; overflow:hidden;}
div#galleries > div > div > img{width:150px; cursor:pointer;}
div#photos{display:none; border:#666 1px solid; padding:20px;}
div#photos > div{float:left; width:125px; height:80px; overflow:hidden; margin:20px;}
div#photos > div > img{width:125px; cursor:pointer;}
div#picbox{display:none; padding-top:36px;}
div#picbox > img{max-width:800px; display:block; margin:0px auto;}
div#picbox > button{ display:block; float:right; font-size:36px; padding:3px 16px;}
</style>
<script src="http://www.timecords.com/js/main.js"></script>
<script src="http://www.timecords.com/js/ajax.js"></script>
<script>
function showGallery(gallery,user){
	_("galleries").style.display = "none";
	_("section_title").innerHTML = user+'&#39;s '+gallery+' Gallery &nbsp; <button onclick="backToGalleries()">Go back to all galleries</button>';
	_("photos").style.display = "block";
	_("photos").innerHTML = 'loading photos ...';
	var ajax = ajaxObj("POST", "http://www.timecords.com/show_gallery");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			_("photos").innerHTML = '';
			var pics = ajax.responseText.split("|||");
			for (var i = 0; i < pics.length; i++){
				var pic = pics[i].split("|");
				_("photos").innerHTML += '<div><img onclick="photoShowcase(\''+pics[i]+'\')" src="http://www.timecords.com/user_data/'+user+'/'+pic[1]+'" alt="pic"><div>';
			}
			_("photos").innerHTML += '<p style="clear:left;"></p>';
		}
	}
	ajax.send("gallery="+gallery+"&user="+user);
}
function backToGalleries(){
	_("photos").style.display = "none";
	_("section_title").innerHTML = "<?php echo $otherProfile['username']; ?>&#39;s Photo Galleries";
	_("galleries").style.display = "block";
}
function photoShowcase(picdata){
	var data = picdata.split("|");
	_("section_title").style.display = "none";
	_("photos").style.display = "none";
	_("picbox").style.display = "block";
	_("picbox").innerHTML = '<button onclick="closePhoto()">x</button>';
	_("picbox").innerHTML += '<img src="http://www.timecords.com/user_data/<?php echo $otherProfile[\'username\']; ?>/'+data[1]+'" alt="photo">';
	if("<?php echo $isOwner ?>" == "yes"){
		_("picbox").innerHTML += '<p id="deletelink"><a href="#" onclick="return false;" onmousedown="deletePhoto(\''+data[0]+'\')">Delete Picture</a></p>';
	}
}
function closePhoto(){
	_("picbox").innerHTML = '';
	_("picbox").style.display = "none";
	_("photos").style.display = "block";
	_("section_title").style.display = "block";
}
function deletePhoto(id){
	var conf = confirm("Press OK to confirm the delete action on this photo.");
	if(conf != true){
		return false;
	}
	_("deletelink").style.visibility = "hidden";
	var ajax = ajaxObj("POST", "htp://www.timecords.com/delete_photo");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "deleted_ok"){
				alert("This picture has been deleted successfully. We will now refresh the page for you.");
				window.location = "http://www.timecords.com/photos/<?php echo $otherProfile['username']; ?>";
			}
		}
	}
	ajax.send("delete=photo&id="+id);
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
  <div id="photo_form"><?php echo $photo_form; ?></div>
  <h2 id="section_title"><?php echo $a; ?> Photo Galleries</h2>
  <div id="galleries"><?php echo $gallery_list; ?></div>
  <div id="photos"></div>
  <div id="picbox"></div>
</div>
<?php
	$this->load->view('templates/template_pageBottom');
?>
</body>
</html>