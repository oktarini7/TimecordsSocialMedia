<?php
$output = "";
if(isset($_POST['u'])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);	
	if ($u == ""){
		// They tried to defeat our security
		echo $output;
		exit;		
	}
	include("php_includes/db_conx.php");	
	$sql = "SELECT username FROM users 
	        WHERE username LIKE '$u%' 
			ORDER BY username ASC";
	$user_query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($user_query);
	if($numrows > 0){
		while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)){
			$uname = $row["username"];
			$output .= '<a href="user.php?u='.$uname.'">'.$uname.'</a><br />';
		}
		echo $output;
		exit;
	} else {
		// No results from search
		echo $output;
		exit;
	}
}
?>