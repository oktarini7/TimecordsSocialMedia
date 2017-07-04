<?php

class Model_friend extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function friend_count($u){
		$query = $this->db->query("SELECT * FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'");
		return $query->num_rows();
	}

	public function get_all_friends($u){
		$all_friends= array();
		$requirements = [
			'user1' => $u,
			'accepted' => '1'
		];
		$this->db->order_by('user2', 'RANDOM');
    	$query = $this->db->get_where('friends', $requirements);
       	foreach ($query->result_array() as $row){
       		array_push($all_friends, $row["user2"]);
       	}
       	$requirements = [
			'user2' => $u,
			'accepted' => '1'
		];
		$this->db->order_by('user1', 'RANDOM');
    	$query = $this->db->get_where('friends', $requirements);
       	foreach ($query->result_array() as $row){
       		array_push($all_friends, $row["user1"]);
       	}
       	return $all_friends;
	}

	public function get_friendsHTML($friends){
		$friendsHTML= "";
		$this->db->select('username, avatar');
		$this->db->from('users');
		$this->db->where_in('username', $friends);
		$query= $this->db->get();
		foreach ($query->result_array() as $row){
       		$friend_username = $row["username"];
			$friend_avatar = $row["avatar"];
			if($friend_avatar != ""){
				$friend_pic = 'http://www.timecords.com/user_data/'.$friend_username.'/'.$friend_avatar;
			} else {
				$friend_pic = 'http://www.timecords.com/images/avatardefault.jpg';
			}
			$friendsHTML .= '<a href="http://www.timecords.com/user/'.$friend_username.'"><img class="friendpics" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
       	}
       	return $friendsHTML;
	}

}
?>