<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function fail_logout()
	{
		echo "The system failed to log you out";
	}

	public function fail_activation()
	{
		echo "Activation failed";
	}

	public function not_loggedin()
	{
		echo "Sorry, you have to log in to visit the page";
	}

	public function not_exist()
	{
		echo "That profile doesn't exist";
	}
}
?>