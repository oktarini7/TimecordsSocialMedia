<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Q2controller extends CI_Controller {

	private $T;
	private $testCasesArray;
	private $error;

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('pages/q2');
	}

	public function buildTestCasesArray($testCasesString){
		return explode(",", $testCasesString);
	}

	public function process(){
		$this->load->model('model_q2');
		$this->T= $this->input->post("T");
		$testCasesString= $this->input->post("q2input");
		$this->testCasesArray= $this->buildTestCasesArray($testCasesString);
		if (!$this->model_q2->checkT($this->T)){
			echo "T must be between 1 &le; T &le; 100";
			exit();
		} else if(($this->T) != (count($this->testCasesArray))){
			echo "T entered is not the same as the actual number of test cases entered";
			exit();
		}else if(preg_match('/\s/',$testCasesString)){
			echo "No whitespace is allowed";
			exit();
		}else if(!preg_match('/^[a-z,]+$/',$testCasesString)){
			echo "Only lower case letters separated by comma allowed";
			exit();
		}else {
			$this->error= $this->model_q2->checkTestCases($this->testCasesArray);
		}
		if ($this->error != 0){
			echo "There are ".$this->error." error(s). Each test case must be between 1 &le; a+b &le; 10000 characters";
			exit();
		} else {
			$output= $this->model_q2->generateSequence($this->testCasesArray);
			echo $output;
			exit();
		}
	}

}
?>