<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indosystem extends CI_Controller {

	function __construct(){ 
		parent::__construct();
        $this->load->model('model_indosystem');
    }
    //ini adalah function php, ketika function dipanggil, output langsung di-print
	public function index()
	{
		$i=1;
		$a= "";
		while ($i<6){
			$j=1;
			$output=$i;
			while ($j<6){
				$a.=$output. " ";
				$output=$i+$output;
				$j++;
			}
			$a.='<br />';
			$i++;
		}
		$allNotes="";
		$allNotes=$this->model_indosystem->getAllNotes();
		$data['a']= $a;
		$data['allNotes']= $allNotes;
		$this->load->view('pages/indosystem_page', $data);
  	}
  	public function submitNote(){
  		$name= $this->input->post("name");
		$address= $this->input->post("address");
		$phone= $this->input->post("phone");
		$note= $this->input->post("note");
		if($this->load->model_indosystem->submitToDatabase($name, $address, $phone, $note)){
			echo "Note anda sudah berhasil disubmit";
		}
  	}
}
?>