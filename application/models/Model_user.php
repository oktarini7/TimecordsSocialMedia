<?php

class Model_user extends CI_Model{

	private $user_profile = false;

	public function __construct(){
		parent::__construct();
	}

	public function getUser($u){
		$requirements = [
			'username' => $u,
			'activated' => '1'
		];
    	$query = $this->db->get_where('users', $requirements, '1');
       	$result= $query->row_array();
		if(!$result){
			return false;
		} else {
			$this->user_profile= $result;
			return $result;
		}
	}

	public function checkFriendship($loggedUser, $otherProfile){
		$requirements = "user1='$loggedUser' AND user2='$otherProfile' AND accepted='1' OR user1='$otherProfile' AND user2='$loggedUser' AND accepted='1'";
    	$query = $this->db->get_where('friends', $requirements, '1');
       	$result= $query->row_array();
		if(!$result){
			return false;
		} else {
			return true;
		}
	}

	public function getAllStatus($loggedUser, $otherProfile, $isFriend, $isOwner){
		$statuslist = "";
		$query = $this->db->query("SELECT * FROM status WHERE account_name='$otherProfile' AND type='a' OR account_name='$otherProfile' AND type='c' ORDER BY postdate DESC");
		$statusnumrows = $query->num_rows();
		if ($statusnumrows > 0){
			foreach ($query->result_array() as $row){
       			$statusid = $row["id"];
				$account_name = $row["account_name"];
				$author = $row["author"];
				$postdate = $row["postdate"];
				$data = $row["data"];
				$data = nl2br($data);
				$data = str_replace("&amp;","&",$data);
				$data = stripslashes($data);
				$statusDeleteButton = '';
				if($author == $loggedUser || $account_name == $loggedUser ){
					$statusDeleteButton = '<span id="sdb_'.$statusid.'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''.$statusid.'\',\'status_'.$statusid.'\');" title="DELETE THIS STATUS AND ITS REPLIES">delete status</a></span> &nbsp; &nbsp;';
				}
			
				// GATHER UP ANY STATUS REPLIES
				$status_replies = "";
				$query_replies = $this->db->query("SELECT * FROM status WHERE osid='$statusid' AND type='b' ORDER BY postdate ASC");
				$replynumrows = $query_replies->num_rows();
				if($replynumrows > 0){
					foreach ($query_replies->result_array() as $row2){
						$statusreplyid = $row2["id"];
						$replyauthor = $row2["author"];
						$replydata = $row2["data"];
						$replydata = nl2br($replydata);
						$replypostdate = $row2["postdate"];
						$replydata = str_replace("&amp;","&",$replydata);
						$replydata = stripslashes($replydata);
						$replyDeleteButton = '';
						if($replyauthor == $loggedUser || $account_name == $loggedUser ){
							$replyDeleteButton = '<span id="srdb_'.$statusreplyid.'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''.$statusreplyid.'\',\'reply_'.$statusreplyid.'\');" title="DELETE THIS COMMENT">remove</a></span>';
						}
						$status_replies .= '<div id="reply_'.$statusreplyid.'" class="reply_boxes"><b>Reply by <a href="http://www.timecords.com/user/'.$replyauthor.'">'.$replyauthor.'</a> '.$replypostdate.':</b> '.$replyDeleteButton.'<br />'.$replydata.'</div>';
					}
				}
				$statuslist .= '<div id="status_'.$statusid.'" class="status_boxes"><div><b>Posted by <a href="http://www.timecords.com/user/'.$author.'">'.$author.'</a> '.$postdate.':</b> '.$statusDeleteButton.' <br />'.$data.'</div>'.$status_replies.'</div>';
				if($isFriend || $isOwner){
				    $statuslist .= '<textarea id="replytext_'.$statusid.'" class="replytext" onkeyup="statusMax(this,250)" placeholder="write a comment here"></textarea><button id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$otherProfile.'\',\'replytext_'.$statusid.'\',this)">Reply</button>';	
				}
			}
       	}
       	return $statuslist;
	}

	public function record_avatar_to_db($file_name, $loggedUser){
		$data = array(
				'avatar' => $file_name
			);
		$this->db->where('username', $loggedUser);
		$this->db->limit(1);
		return $this->db->update('users', $data);
	}

}
?>