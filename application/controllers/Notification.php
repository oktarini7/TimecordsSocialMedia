<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_check_login_status');
		$this->load->model('model_user');
		$this->load->model('model_notification');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if (!$user_ok2){
			redirect('message/not_loggedin');
			exit();
		}
	}

	public function index()
	{
		$loggedUser= $this->model_check_login_status->get_log_username();
		$isOwner= true;

		//notification list
		$notification_list = $this->model_notification->getNotificationList($loggedUser);

		//friend requests
		$friend_requests= $this->model_notification->getFriendRequests($loggedUser);

		$data['isOwner']= $isOwner;
		$data['otherProfile']= $this->model_user->getUser($loggedUser);
		$data['loggedUser']= $this->model_user->getUser($loggedUser);
		$data['notification_list']= $notification_list;
		$data['friend_requests']= $friend_requests;
		$this->load->view('pages/notification', $data);
	}

}
?>