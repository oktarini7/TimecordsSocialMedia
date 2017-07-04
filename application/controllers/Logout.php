<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function logoutNow()
	{
		// Expire their cookie files
		if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
			setcookie("id", '', strtotime( '-5 days' ), '/');
    		setcookie("user", '', strtotime( '-5 days' ), '/');
			setcookie("pass", '', strtotime( '-5 days' ), '/');
		}
		// Destroy the session variables
		unset($_SESSION['userid'], $_SESSION['username'], $_SESSION['password']);
		// Double check to see if their sessions exists
		if(isset($_SESSION['username'])){
			redirect('message/fail_logout');
		} else {
			redirect('http://www.timecords.com');
			exit();
		}
	}
}
?>