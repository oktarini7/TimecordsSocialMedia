<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_check_login_status');
		$this->load->model('model_user');
		$this->load->model('model_status');
		$this->load->model('model_friend');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if (!$user_ok2){
			redirect('message/not_loggedin');
			exit();
		}
	}

	public function posttostatus()
	{
		$type= $this->input->post("type");
		$account_name= $this->input->post("user");
		$data= $this->input->post("data");
		$loggedUser= $this->model_check_login_status->get_log_username();
		// Make sure post data is not empty
		if(strlen($data) < 1){
		    echo "data_empty";
		    exit();
		}
		// Make sure type is either a or c
		if($type != "a" && $type != "c"){
		    echo "type_unknown";
		    exit();
		}
		$otherProfile= $this->model_user->getUser($account_name);
		if (!$otherProfile){
			redirect('message/not_exist');
			exit();
		} else {
			$id= $this->model_status->insertStatus($account_name, $loggedUser, $type, $data);
			$friend_count= $this->model_friend->friend_count($loggedUser);
			$friends= array();
			if ($friend_count >= 1){
				$friends= $this->model_friend->get_all_friends($loggedUser);
				$this->model_status->insertNotification($friends, $account_name, $loggedUser, $id);
			}
			echo "post_ok|$id";
			exit();
		}
	}

	public function replytostatus(){
		$osid= $this->input->post("sid");
		$account_name= $this->input->post("user");
		$data= $this->input->post("data");
		$loggedUser= $this->model_check_login_status->get_log_username();
		// Make sure post data is not empty
		if(strlen($data) < 1){
		    echo "data_empty";
		    exit();
		}
		$otherProfile= $this->model_user->getUser($account_name);
		if (!$otherProfile){
			redirect('message/not_exist');
			exit();
		} else {
			$id= $this->model_status->insertReply($osid, $account_name, $loggedUser, 'b', $data);
			$this->model_status->insertReplyNotification($osid, $id, $loggedUser, $account_name);
			echo "reply_ok|$id";
			exit();
		}
	}

	public function deletestatus(){
		$statusid= $this->input->post("statusid");
		$loggedUser= $this->model_check_login_status->get_log_username();
		if($statusid == ""){
			echo "status id is missing";
			exit();
		}
		$success= $this->model_status->deletestatus($statusid, $loggedUser);
		if($success){
			echo "delete_ok";
			exit();
		} else {
			echo "something went wrong, we cannot delete the status";
			exit();
		}
	}

	public function deletereply(){
		$replyid= $this->input->post("replyid");
		$loggedUser= $this->model_check_login_status->get_log_username();
		if($replyid == ""){
			echo "reply id is missing";
			exit();
		}
		$success= $this->model_status->deletereply($replyid, $loggedUser);
		if($success){
			echo "delete_ok";
			exit();
		} else {
			echo "something went wrong, we cannot delete the status";
			exit();
		}
	}

}
?>