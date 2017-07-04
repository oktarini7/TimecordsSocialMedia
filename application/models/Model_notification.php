<?php

class Model_notification extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function getNotificationList($loggedUser){
		$notification_list = "";
		$query = $this->db->query("SELECT * FROM notifications WHERE username LIKE BINARY '$loggedUser' ORDER BY date_time DESC");
		$numrows = $query->num_rows();
		if ($numrows < 1){
			return "You do not have any notifications";
		} else{
			foreach ($query->result_array() as $row){
				$noteid = $row["id"];
				$initiator = $row["initiator"];
				$app = $row["app"];
				$note = $row["note"];
				$date_time = $row["date_time"];
				$notification_list .= "<p><a href='http://www.timecords.com/user/$initiator'>$initiator</a> | $app<br />$note</p>";
			}
			return $notification_list;
		}
	}

	public function getFriendRequests($loggedUser){
		$friend_requests = "";
		$query = $this->db->query("SELECT * FROM friends WHERE user2='$loggedUser' AND accepted='0' ORDER BY datemade ASC");
		$numrows = $query->num_rows();
		if ($numrows < 1){
			return "No friend requests";
		} else{
			foreach ($query->result_array() as $row){
				$reqID = $row["id"];
				$user1 = $row["user1"];
				$datemade = $row["datemade"];
				$query2 = $this->db->query("SELECT avatar FROM users WHERE username='$user1' LIMIT 1");
				$query2result= $query2->row_array();
				$user1avatar = $query2result['avatar'];
				$user1pic = '<img src="http://www.timecords.com/user/'.$user1.'/'.$user1avatar.'" alt="'.$user1.'" class="user_pic">';
				if($user1avatar == NULL){
					$user1pic = '<img src="http://www.timecords.com/images/avatardefault.jpg" alt="'.$user1.'" class="user_pic">';
				}
				$friend_requests .= '<div id="friendreq_'.$reqID.'" class="friendrequests">';
				$friend_requests .= '<a href="http://www.timecords.com/user/'.$user1.'">'.$user1pic.'</a>';
				$friend_requests .= '<div class="user_info" id="user_info_'.$reqID.'">'.$datemade.' <a href="http://www.timecords.com/user/'.$user1.'">'.$user1.'</a> requests friendship<br /><br />';
				$friend_requests .= '<button onclick="friendReqHandler(\'accept\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">accept</button> or ';
				$friend_requests .= '<button onclick="friendReqHandler(\'reject\',\''.$reqID.'\',\''.$user1.'\',\'user_info_'.$reqID.'\')">reject</button>';
				$friend_requests .= '</div>';
				$friend_requests .= '</div>';
			}
			return $friend_requests;
		}

	}

}
?>