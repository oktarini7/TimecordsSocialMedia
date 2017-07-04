<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friendsystem extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_user');
		$this->load->model('model_friend_system');
		$this->load->model('model_check_login_status');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if (!$user_ok2){
			redirect('message/not_loggedin');
			exit();
		}
	}

	public function action()
	{
		$type= $this->input->post('type');
		$user= $this->input->post('user');
		$otherProfileArray= $this->model_user->getUser($user);
		$otherProfile= $otherProfileArray['username'];
		$loggedUser= $this->model_check_login_status->get_log_username();
		if (!$otherProfile){
			redirect('message/not_exist');
			exit();
		}
		$status= "";
		if ($type== "friend"){
			$status= $this->model_friend_system->checkFriendshipStatus($loggedUser, $otherProfile);
			if($status == "already_friends"){
				echo "You are already friends with $otherProfile";
				exit();
			} else if ($status == "pending_request"){
				echo "You have a pending friend request already sent to $otherProfile.";
				exit();
			} else if ($status == "pending_request_for_you"){
				echo "$otherProfile has requested to friend with you first. Check your friend requests.";
				exit();
			} else {
				$this->model_friend_system->sendFriendRequest($loggedUser, $otherProfile);
				echo "friend_request_sent";
				exit();
			}
		} else if($type =="unfriend"){
			$unfriend= $this->model_friend_system->cutFriendship($loggedUser, $otherProfile);
			if ($unfriend){
				echo "unfriend_ok";
				exit();
			} else {
				echo "No friendship could be found between your account and $otherProfile, therefore we cannot unfriend you.";
				exit();
			}
		}
	}

	public function friendrequest(){
		$action= $this->input->post('action');
		$reqid= $this->input->post('reqid');
		$user1= $this->input->post('user1');
		$otherProfileArray= $this->model_user->getUser($user1);
		$otherProfile= $otherProfileArray['username'];
		$loggedUser= $this->model_check_login_status->get_log_username();
		if (!$otherProfile){
			redirect('message/not_exist');
			exit();
		}
		if($action == "accept"){
			$result= $this->model_friend_system->acceptFriendRequest($reqid, $user1, $loggedUser);
			echo $result;
			exit();
		} else if($action == "reject"){
			$result= $this->model_friend_system->rejectFriendRequest($reqid, $user1, $loggedUser);
			echo $result;
			exit();
		}
	}

}
?>