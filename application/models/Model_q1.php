<?php

class Model_q1 extends CI_Model{

	private $output= "";

	public function __construct(){
		parent::__construct();
	}

	public function checkInput($n){
		if ($n > 0 && $n <200000){
			return true;
		} else {
			return false;
		}
	}

	public function generateSequence($n){
		$i=1;
		$internaloutput= "";
		while ($i <= $n){
			if (($i % 3) == 0){
				$internaloutput.= "Fizz";
				if (($i % 5) == 0){
					$internaloutput.= "Buzz";
				}
				$internaloutput.= "<br />";
			} else if (($i % 5) == 0){
				$internaloutput.= "Buzz<br />";
			} else {
				$internaloutput.= $i . "<br/>";
			}
			$i++;
		}
		$this->output= $internaloutput;
		return $this->output;
	}

}
?>