<?php

class Model_photos extends CI_Model{

	private $user_profile = false;

	public function __construct(){
		parent::__construct();
	}

	public function record_upload_to_db($gallery, $file_name, $loggedUser){
		$data = array(
				'user' => $loggedUser,
				'gallery' => $gallery,
				'filename' => $file_name,
				'uploaddate' => date('Y-m-d H:i:s')
			);
		$this->db->insert('photos', $data);
	}

	public function count_gallery($loggedUser){
		$query = $this->db->query("SELECT DISTINCT gallery FROM photos WHERE user='$loggedUser'");
		$numrows = $query->num_rows();
		return $numrows;
	}

	public function get_gallery_list($loggedUser){
		$gallery_list= "";
		$query = $this->db->query("SELECT DISTINCT gallery FROM photos WHERE user='$loggedUser'");
		foreach ($query->result_array() as $row){
			$gallery = $row["gallery"];
			//count num of photos in the gallery
			$requirements = [
				'user' => $loggedUser,
				'gallery' => $gallery
			];
    		$query = $this->db->get_where('photos', $requirements);
       		$count= $query->num_rows();
       		//pick 1 photo from the gallery
       		$requirements = [
				'user' => $loggedUser,
				'gallery' => $gallery
			];
			$this->db->order_by('id', 'RANDOM');
    		$query = $this->db->get_where('photos', $requirements, '1');
       		$filerow = $query->row_array();
       		$file= $filerow['filename'];
       		$gallery_list .= '<div>';
			$gallery_list .=   '<div onclick="showGallery(\''.$gallery.'\',\''.$loggedUser.'\')">';
			$gallery_list .=     '<img src="http://www.timecords.com/user_data/'.$loggedUser.'/'.$file.'" alt="cover photo">';
			$gallery_list .=   '</div>';
			$gallery_list .=   '<b>'.$gallery.'</b> ('.$count.')';
			$gallery_list .= '</div>';
		}
		return $gallery_list;
	}

	public function getPhotosFromGallery($gallery, $user){
		$requirements = [
				'gallery' => $gallery,
				'user' => $user
			];
		$this->db->order_by('uploaddate', 'ASC');
		$query = $this->db->get_where('photos', $requirements);
		$picstring='';
		foreach ($query->result_array() as $row){
			$id = $row["id"];
			$filename = $row["filename"];
			$picstring .= "$id|$filename|||";
		}
		$picstring = trim($picstring, "|||");
		return $picstring;
	}

}
?>