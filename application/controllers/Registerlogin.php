<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registerlogin extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_registerlogin');
		$this->load->model('model_check_login_status');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if ($user_ok2){
			$log_u= $this->model_check_login_status->get_log_username();
			redirect('user/'. $log_u);
			exit();
		}
	}

	public function index()
	{
		$this->load->view('pages/registerlogin');
	}

	public function login()
	{
		$eLogin= $this->input->post("e_login");
		$pLogin= $this->input->post("p_login");
		$ip= $this->input->ip_address();

		if($eLogin == "" || $pLogin == ""){
			echo "login_failed";
        	exit();
		} else {
			$user= $this->model_registerlogin->getUser($eLogin);
			if(password_verify($pLogin, $user['password'])){
				$_SESSION['userid'] = $user['id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['password'] = $user['password'];
				setcookie("id", $user['id'], strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("user", $user['username'], strtotime( '+30 days' ), "/", "", "", TRUE);
    			setcookie("pass", $user['password'], strtotime( '+30 days' ), "/", "", "", TRUE);
    			$this->model_registerlogin->updateLastLogin($user['id'], $ip);
    			echo $user['username'];
    			exit();
			} else {
				echo "login_failed";
				exit();
			}
		}
	}

	public function check_username()
	{
		$username= $this->input->post("usernamecheck");
		$uname_check= $this->model_registerlogin->checkUsername($username);
		if (strlen($username) < 3 || strlen($username) > 16) {
	    	echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
	    	exit();
    	}
		if (is_numeric($username[0])) {
	    	echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
	    	exit();
    	}
    	if (!$uname_check) {
	    	echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
	    	exit();
    	} else {
	    	echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
	    	exit();
    	}
	}

	public function register()
	{
		$u = $this->input->post("u");
		$e = $this->input->post("e");
		$p = $this->input->post("p");
		$g = $this->input->post("g");
		$ip= $this->input->ip_address();
		$u_check= $this->model_registerlogin->checkUsername($u);
		$e_check= $this->model_registerlogin->checkEmail($e);
		if($u == "" || $e == "" || $p == "" || $g == ""){
			echo "The form submission is missing values.";
        	exit();
		} else if ($u_check){ 
        	echo "The username you entered is alreay taken";
        	exit();
		} else if ($e_check){ 
        	echo "That email address is already in use in the system";
        	exit();
		} else if (strlen($u) < 3 || strlen($u) > 16) {
        	echo "Username must be between 3 and 16 characters";
        	exit(); 
    	} else if (is_numeric($u[0])) {
        	echo 'Username cannot begin with a number';
        	exit();
    	} else if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $p)){
    		echo 'Password must contain minimum eight characters, at least one uppercase letter, one lowercase letter and one number';
    		exit();
    	} else {
    		$p_hash= password_hash($p, PASSWORD_DEFAULT);
    		// Add user info into the database table for the main site table
    		$uid= $this->model_registerlogin->insertUser($u, $e, $p_hash, $g, $ip);
    		// Create directory(folder) to hold each user's files(pics, MP3s, etc.)
    		$path= APPPATH . "../user_data/" . $u;
			if(!is_dir($path))
			{
				mkdir ($path, 0755);
			}
			$token= $this->session->userdata('token_timecords');
			$this->load->library('email');
			$this->email->from('activation@timecords.com', 'Timecords');
			$this->email->to($e);
			$this->email->subject('Account activation');
			$mess= '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Timecords Account Activation</title></head><body>Click <a href="http://www.timecords.com/activation/'.$uid.'/'.$u.'/'.$e.'/'.$token.'">here</a>to activate your account</a><br /><br />Login after successful activation using your e-mail address <b>'.$e.'</b></div></body></html>';
			$this->email->set_header('From','activation@timecords.com');
			$this->email->set_header('Content-type', 'text/html');
			$this->email->set_header('charset', 'iso-8859-1');
			$this->email->message($mess);
			$this->email->send();
    		/*$to = $e;							 
			$from = 'activation@timecords.com';
			$subject = 'Timecords Account Activation';
			$message = '<html>Click <a href="http://www.timecords.com/activation/'.$uid.'/'.$u.'/'.$e.'/'.$token.'">here</a>to activate your account</a><br /><br />Login after successful activation using your e-mail address <b>'.$e.'</b></div></html>';
			$headers = 'From: '.$from.'\n';
        	$headers .= 'MIME-Version: 1.0\n';
        	$headers .= 'Content-type: text/html; charset=iso-8859-1\n';
			mail($to, $subject, $message, $headers);*/
			echo 'signup_success';
			exit();
		}
		exit();
	}

	public function logout()
	{
		// Expire their cookie files
		if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])) {
			setcookie("id", '', strtotime( '-5 days' ), '/');
    		setcookie("user", '', strtotime( '-5 days' ), '/');
			setcookie("pass", '', strtotime( '-5 days' ), '/');
		}
		// Destroy the session variables
		session_destroy();
		// Double check to see if their sessions exists
		if(isset($_SESSION['username'])){
			redirect('message/fail_logout');
		} else {
			redirect('http://www.timecords.com');
			exit();
		}
	}
	public function activate($id, $u, $e, $t){
		$user_ok= $this->model_registerlogin->verifyActivation($id, $u, $e, $t);
		if (!$user_ok){
			redirect('message/fail_activation');
			exit();
		} else {
			$activation= $this->model_registerlogin->activateNow($u);
			if (!$activation){
				redirect('message/fail_activation');
			} else {
				$this->load->view('pages/activation');
			}
		}
	}
}
?>