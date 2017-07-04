<?php

class Model_q2 extends CI_Model{

	private $testCasesArray;
	private $output;

	public function __construct(){
		parent::__construct();
	}

	public function checkT($T){
		if ($T >=1 && $T <=100){
			return true;
		} else {
			return false;
		}
	}

	public function checkTestCases($testCasesArray){
		$error=0;
		$i=0;
		while($i < count($testCasesArray)){
			if (strlen($testCasesArray[$i])<1 || strlen($testCasesArray[$i])>10000){
				$error++;
			}
			$i++;
		}
		return $error;
	}

	public function generateSequence($testCasesArray){
		$localoutput= "";
		$i=0;
		while($i<count($testCasesArray)){
			if ((strlen($testCasesArray[$i]) % 2) == 1){
				$localoutput.= "-1 <br />";
			} else {
				$anagramError=0;
				$eachTestCase= str_split($testCasesArray[$i]);
				$word_length= (count($eachTestCase))/2;
				for($a=0; $a<$word_length; $a++){
					$b=count($eachTestCase)-1-$a; //$a is the pointer for word a,$b is the pointer for word b
					if ($eachTestCase[$a] != $eachTestCase[$b]){
						$anagramError++;
					}
				}
				$localoutput.= $anagramError."<br />";
			}
			$i++;
		}
		$this->output= $localoutput;
		return $this->output;
	}

}
?>