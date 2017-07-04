<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Q1controller extends CI_Controller {

	private $q1input;
	private $q1output;

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('pages/q1');
	}

	public function process(){
		$this->load->model('model_q1');
		$this->q1input= $this->input->post("q1input");
		if (!$this->model_q1->checkInput($this->q1input)){
			echo "Input must be between 0 &#60; n &#60; 2x10<sup>5</sup>";
			exit();
		} else {
			$this->q1output= $this->model_q1->generateSequence($this->q1input);
			echo $this->q1output;
			exit();
		}
	}

}
?>