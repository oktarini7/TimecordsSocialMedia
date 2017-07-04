<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friends extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_check_login_status');
		$this->load->model('model_user');
		$this->load->model('model_friend');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if (!$user_ok2){
			redirect('message/not_loggedin');
			exit();
		}
	}

	public function index($u)
	{
		$otherProfileArray= $this->model_user->getUser($u);
		$otherProfile= $otherProfileArray['username'];
		$loggedUser= $this->model_check_login_status->get_log_username();
		if (!$otherProfile){
			redirect('message/not_exist');
			exit();
		} else {
			$isOwner= false;
			$a= $otherProfile . "'s";
			$friendsTitle= $otherProfile . " has";
			if ($otherProfile == $loggedUser)
			{
				$isOwner= true;
				$a= "My";
				$friendsTitle= "You have";
			}
			$friend_count= $this->model_friend->friend_count($otherProfile);
			$friendsHTML= "";
			if ($friend_count >= 1) {
				$friends= $this->model_friend->get_all_friends($otherProfile);
				$friendsHTML= $this->model_friend->get_friendsHTML($friends);
			}

			$data['otherProfile']= $otherProfileArray;
			$data['loggedUser']= $this->model_user->getUser($loggedUser);
			$data['isOwner']= $isOwner;
			$data['a']= $a;
			$data['friendsTitle']= $friendsTitle;
			$data['friend_count']= $friend_count;
			$data['friendsHTML']= $friendsHTML;
			$this->load->view('pages/friends', $data);
		}
	}

}
?>