<?php

class Model_status extends CI_Model{

	public function __construct(){
		parent::__construct();
	}

	public function insertStatus($account_name, $loggedUser, $type, $data){
		$data=[
			'account_name' => $account_name,
			'author' => $loggedUser,
			'type' => $type,
			'data' => $data,
			'postdate' => date('Y-m-d H:i:s')
		];
		$this->db->insert('status', $data);
		$id = $this->db->insert_id();
		$data = array(
				'osid' => $id
			);
		$this->db->where('id', $id);
		$this->db->limit(1);
		$this->db->update('status', $data);
		return $id;
	}

	public function insertNotification($friends, $account_name, $loggedUser, $id){
		$data= array();
		for($i = 0; $i < count($friends); $i++){
			$childdata= array(
				'username' => $friends[$i],
				'initiator' => $loggedUser,
				'app' => "Status Post",
				'note' => $loggedUser.' posted on: <br /><a href="http://www.timecords.com/user/'.$account_name.'#status_'.$id.'">'.$account_name.'&#39;s Profile</a>',
				'date_time' => date('Y-m-d H:i:s')
			);
			array_push($data, $childdata);
		}
		$this->db->insert_batch('notifications', $data);
	}

	public function insertReply($osid, $account_name, $loggedUser, $type, $data){
		$data=[
			'osid' => $osid,
			'account_name' => $account_name,
			'author' => $loggedUser,
			'type' => $type,
			'data' => $data,
			'postdate' => date('Y-m-d H:i:s')
		];
		$this->db->insert('status', $data);
		$id = $this->db->insert_id();
		return $id;
	}

	public function insertReplyNotification($osid, $id, $loggedUser, $account_name){
		$participants= array();
		$query = $this->db->query("SELECT author FROM status WHERE osid='$osid' AND author!='$loggedUser' GROUP BY author");
		$numparticipants = $query->num_rows();
		if ($numparticipants > 0){
			foreach ($query->result_array() as $row){
				array_push($participants, $row['author']);
			}
       		$data= array();
			for($i = 0; $i < count($participants); $i++){
				$childdata= array(
					'username' => $participants[$i],
					'initiator' => $loggedUser,
					'app' => "Status Reply",
					'note' => $loggedUser.' commented here:<br /><a href="http://www.timecords.com/user/'.$account_name.'#status_'.$osid.'">Click here to view the conversation</a>',
					'date_time' => date('Y-m-d H:i:s')
				);
				array_push($data, $childdata);
			}
			$this->db->insert_batch('notifications', $data);
		}
    }

    public function deletestatus($statusid, $loggedUser){
    	// Check to make sure this logged in user actually owns that comment
    	$requirements = [
			'id' => $statusid
		];
    	$query = $this->db->get_where('status', $requirements, '1');
       	$result= $query->row_array();
       	$account_name = $result["account_name"]; 
		$author = $result["author"];
		if ($author == $loggedUser || $account_name == $loggedUser) {
			$this->db->where('osid', $statusid);
			$this->db->delete('status');
	    	return true;
		} else {
			return false;
		}
    }

    public function deletereply($replyid, $loggedUser){
    	// Check to make sure this logged in user actually owns that comment
    	$requirements = [
			'id' => $replyid
		];
    	$query = $this->db->get_where('status', $requirements, '1');
       	$result= $query->row_array();
       	$account_name = $result["account_name"]; 
		$author = $result["author"];
		if ($author == $loggedUser || $account_name == $loggedUser) {
			$this->db->where('id', $replyid);
			$this->db->delete('status');
	    	return true;
		} else {
			return false;
		}
    }


}
?>