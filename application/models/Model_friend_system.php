<?php

class Model_friend_system extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function checkFriendshipStatus($loggedUser, $otherProfile){
		$requirements = "user1='$loggedUser' AND user2='$otherProfile' AND accepted='1' OR user1='$otherProfile' AND user2='$loggedUser' AND accepted='1'";
    	$query = $this->db->get_where('friends', $requirements, '1');
       	$result= $query->row_array();
		if($result){
			return "already_friends";
		} else {
			$requirements2 =[
				'user1' => $loggedUser,
				'user2' => $otherProfile,
				'accepted' => '0'
			];
    		$query2 = $this->db->get_where('friends', $requirements2, '1');
       		$result2 = $query2->row_array();
       		if($result2){
				return "pending_request";
			} else {
				$requirements3 =[
				'user1' => $otherProfile,
				'user2' => $loggedUser,
				'accepted' => '0'
				];
    			$query3 = $this->db->get_where('friends', $requirements3, '1');
       			$result3 = $query3->row_array();
       			if($result3){
					return "pending_request_for_you";
				} else {
					return "";
				}
			}
		}
	}

	public function sendFriendRequest($loggedUser, $otherProfile){
		$data=[
			'user1' => $loggedUser,
			'user2' => $otherProfile,
			'datemade' => date('Y-m-d H:i:s')
		];
		$this->db->insert('friends', $data);
	}

	public function cutFriendship($loggedUser, $otherProfile){
		$requirements1 =[
			'user1' => $loggedUser,
			'user2' => $otherProfile,
			'accepted' => '1'
		];
    	$query1 = $this->db->get_where('friends', $requirements1, '1');
   		$whoRequest1 = $query1->row_array();
   		if ($whoRequest1){
   			$requirements2= [
   				'user1' => $loggedUser,
				'user2' => $otherProfile,
				'accepted' => '1'
   			];
   			$this->db->delete('friends', $requirements2);
   			return true;
   		} else {
   			$requirements3 =[
				'user1' => $otherProfile,
				'user2' => $loggedUser,
				'accepted' => '1'
			];
    		$query3 = $this->db->get_where('friends', $requirements3, '1');
   			$whoRequest3 = $query3->row_array();
   			if ($whoRequest3){
   				$requirements4= [
   					'user1' => $otherProfile,
					'user2' => $loggedUser,
					'accepted' => '1'
   				];
   				$this->db->delete('friends', $requirements4);
   				return true;
   			} else {
   				return false;
   			}
   		}
	}

	public function acceptFriendRequest($reqid, $user1, $loggedUser){
		$data = array(
        	'accepted' => '1'
        );
		$this->db->where('id', $reqid);
		$this->db->update('friends', $data);
		return "accept_ok";
	}

	public function rejectFriendRequest($reqid, $user1, $loggedUser){
		$requirements= [
				'id' => $reqid,
   				'user1' => $user1,
				'user2' => $loggedUser,
				'accepted' => '0'
   			];
   		$this->db->delete('friends', $requirements);
   		return "reject_ok";
	}
}
?>