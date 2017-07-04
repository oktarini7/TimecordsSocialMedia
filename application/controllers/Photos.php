<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Photos extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_check_login_status');
		$this->load->model('model_user');
		$this->load->model('model_photos');
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
			$photo_form= "";

			if ($otherProfile == $loggedUser)
			{
				$isOwner= true;
				$a= "My";
				$photo_form= $this->photoForm($loggedUser);
			}

			$gallery_list="";
			$num_of_gallery= $this->model_photos->count_gallery($loggedUser);
			if ($num_of_gallery<1){
				$gallery_list = "This user has not uploaded any photos yet.";
			} else {
				$gallery_list= $this->model_photos->get_gallery_list($loggedUser);
			}

			$data['otherProfile']= $otherProfileArray;
			$data['loggedUser']= $this->model_user->getUser($loggedUser);
			$data['isOwner']= $isOwner;
			$data['a']= $a;
			$data['photo_form']= $photo_form;
			$data['gallery_list']= $gallery_list;
			$this->load->view('pages/photos', $data);
		}
	}

	private function photoForm($loggedUser){
		$photo_form  = '<form id="photo_form" enctype="multipart/form-data" method="post" action="http://www.timecords.com/upload_pict">';
		$photo_form .=   '<h3>Hi '.$loggedUser.', add a new photo into one of your galleries</h3>';
		$photo_form .=   '<b>Choose Gallery:</b> ';
		$photo_form .=   '<select name="gallery" required>';
		$photo_form .=     '<option value=""></option>';
		$photo_form .=     '<option value="Me">Me</option>';
		$photo_form .=     '<option value="Family">Family</option>';
		$photo_form .=     '<option value="Friends">Friends</option>';
		$photo_form .=     '<option value="Others">Others</option>';
		$photo_form .=   '</select>';
		$photo_form .=   ' &nbsp; &nbsp; &nbsp; <b>Choose Photo:</b> ';
		$photo_form .=   '<input type="file" name="photo" accept="image/*" required>';
		$photo_form .=   '<p><input type="submit" value="Upload Photo Now"></p>';
		$photo_form .= '</form>';
		return $photo_form;
	}

	public function upload_pict(){
		$loggedUser= $this->model_check_login_status->get_log_username();
		$config['upload_path']          = APPPATH.'../user_data/'.$loggedUser;
		$config['allowed_types']        = 'gif|jpeg|png';
        $config['max_size']             = 2048;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('photo')){
            $uploadError = array('error' => $this->upload->display_errors());
            echo $uploadError['error'];
        }else{
            $upload_data = $this->upload->data();
   			$file_name = $upload_data['file_name'];
   			$kaboom = explode(".", $file_name);
			$fileExt = end($kaboom);
			$gallery= $this->input->post('gallery');
			$width= $this->upload->data('image_width');
			$height= $this->upload->data('image_height');
			$wmax = 200;
			$hmax = 300;
			if($width > $wmax || $height > $hmax){
   				$target_file = APPPATH.'../user_data/'.$loggedUser.'/'.$file_name;
				$resized_file = APPPATH.'../user_data/'.$loggedUser.'/'.$file_name;
				$this->img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
			}
			$this->model_photos->record_upload_to_db($gallery, $file_name, $loggedUser);
            redirect('photos/'. $loggedUser);
        }
	}

	private function img_resize($target, $newcopy, $w, $h, $ext) {
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

	public function delete_photo(){
		$id= $this->input->post('id');
		$requirements = [
			'id' => $id
		];
    	$query = $this->db->get_where('photos', $requirements, '1');
       	$result= $query->row_array();
       	$loggedUser= $this->model_check_login_status->get_log_username();
       	$user= $result['user'];
       	$filename= $result['filename'];
       	if($user == $loggedUser){
			$picurl = APPPATH. "../user_data/".$loggedUser."/".$filename; 
	    	if (file_exists($picurl)) {
				unlink($picurl);
				$this->db->where('id', $id);
				$this->db->delete('photos');
			}
		}
		echo "deleted_ok";
	}

	public function show_gallery(){
		$gallery= $this->input->post('gallery');
		$user= $this->input->post('user');
		$picstring = $this->model_photos->getPhotosFromGallery($gallery, $user);
		echo $picstring;
	}

}
?>