<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_check_login_status');
		$this->load->model('model_user');
		$user_ok2= false;
		$user_ok2= $this->model_check_login_status->check_login();
		if (!$user_ok2){
			redirect('message/not_loggedin');
			exit();
		}
	}

	public function index($u)
	{
		$otherProfile= $this->model_user->getUser($u);
		$loggedUser= $this->model_user->getUser($this->model_check_login_status->get_log_username());
		if (!$otherProfile){
			redirect('message/not_exist');
			exit();
		} else {
			$isOwner= false;
			$profile_pic_btn= "";
			$avatar_form= "";

			if ($otherProfile['username'] == $loggedUser['username'])
			{
				$isOwner= true;
				$profile_pic_btn = '<a href="#" id="toggleAvatar" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Upload Profile Picture</a>';
				$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="http://www.timecords.com/upload_profpict">';
				$avatar_form .=   '<h4>Change your profile picture</h4>';
				$avatar_form .=   '<input type="file" name="avatar" required>';
				$avatar_form .=   '<p><input type="submit" value="Upload"></p>';
				$avatar_form .= '</form>';
			}
			
			$profile_pic = '<img src="http://www.timecords.com/user_data/'.$otherProfile['username'].'/'.$otherProfile['avatar'].'" alt="'.$otherProfile['username'].'">';
			if($otherProfile['avatar'] == NULL){
				$profile_pic = '<img src="http://www.timecords.com/images/avatardefault.jpg" alt="'.$otherProfile['username'].'">';
			}

			$isFriend= false;
			$friendButton= "";
			if(!$isOwner){
				$isFriend= $this->model_user->checkFriendship($loggedUser['username'], $otherProfile['username']);
				if(!$isFriend){
					$friendButton= '<button onclick="friendToggle(\'friend\',\''.$otherProfile['username'].'\',\'friendBtn\')">Request As Friend</button>';
				} else {
					$friendButton= '<button onclick="friendToggle(\'unfriend\',\''.$otherProfile['username'].'\',\'friendBtn\')">Unfriend</button>';
				}
			}

			$status_ui = "";
			$statuslist = "";
			if($isOwner){
				$status_ui = '<textarea id="statustext" onkeyup="statusMax(this,250)" placeholder="'.$loggedUser['username'].', what are you thinking about?"></textarea>';
				$status_ui .= '<button id="statusBtn" onclick="postToStatus(\'status_post\',\'a\',\''.$otherProfile['username'].'\',\'statustext\')">Post</button>';
			} else if($isFriend){
				$status_ui = '<textarea id="statustext" onkeyup="statusMax(this,250)" placeholder="Hi '.$loggedUser['username'].', say something to '.$otherProfile['username'].'"></textarea>';
				$status_ui .= '<button id="statusBtn" onclick="postToStatus(\'status_post\',\'c\',\''.$otherProfile['username'].'\',\'statustext\')">Post</button>';
			}

			$statuslist= $this->model_user->getAllStatus($loggedUser['username'], $otherProfile['username'], $isFriend, $isOwner);

			$data['isOwner']= $isOwner;
			$data['otherProfile']= $otherProfile;
			$data['profile_pic']= $profile_pic;
			$data['loggedUser']= $loggedUser;
			$data['profile_pic_btn']= $profile_pic_btn;
			$data['avatar_form']= $avatar_form;
			
			$data['isFriend']= $isFriend;
			$data['friendButton']= $friendButton;

			$data['status_ui']= $status_ui;
			$data['statuslist']= $statuslist;
			$this->load->view('pages/user', $data);
		}
	}

	public function upload_profpict(){
		$loggedUser= $this->model_check_login_status->get_log_username();
		$config['upload_path']          = APPPATH.'../user_data/'.$loggedUser;
		$config['allowed_types']        = 'gif|jpeg|png';
        $config['max_size']             = 2048;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('avatar')){
            $uploadError = array('error' => $this->upload->display_errors());
            echo $uploadError['error'];
        }else{
            $upload_data = $this->upload->data();
   			$file_name = $upload_data['file_name'];
   			$kaboom = explode(".", $file_name);
			$fileExt = end($kaboom);
   			$target_file = APPPATH.'../user_data/'.$loggedUser.'/'.$file_name;
			$resized_file = APPPATH.'../user_data/'.$loggedUser.'/'.$file_name;
			$wmax = 200;
			$hmax = 300;
			$this->img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
			$this->model_user->record_avatar_to_db($file_name, $loggedUser);
            redirect('user/'. $loggedUser);
        }
	}

	function img_resize($target, $newcopy, $w, $h, $ext) {
    	list($w_orig, $h_orig) = getimagesize($target);
    	$scale_ratio = $w_orig / $h_orig;
    	if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    	} else {
           $h = $w / $scale_ratio;
    	}
    	$img = "";
    	$ext = strtolower($ext);
    	if ($ext == "gif"){ 
    		$img = imagecreatefromgif($target);
    	} else if($ext =="png"){ 
    		$img = imagecreatefrompng($target);
    	} else { 
    		$img = imagecreatefromjpeg($target);
    	}
    	$tci = imagecreatetruecolor($w, $h);
    	// imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    	imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    	imagejpeg($tci, $newcopy, 84);
	}

}
?>