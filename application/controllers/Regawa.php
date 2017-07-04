<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regawa extends CI_Controller {

	function __construct(){ 
		parent::__construct();
        $this->load->helper('url'); 
        $this->load->helper('file');    
    }
	public function index()
	{
    	$string = file_get_contents('http://www.timecords.com/task1.txt');
        //$timeArray= preg_match_all("/\[[a-zA-Z]{3}\040[a-zA-Z]{3}\040\d{2}\040\d{2}:\d{2}:\d{2}\040\d{4}\]/", $string, $matches);
        //var_dump($matches[0]);
        //$errorArray= preg_match_all("/[\S*]+error\S*]+/", $string, $matches2);
        //var_dump($matches2[0]);
        //$IPArray= preg_match_all("/\[client\040[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+\]/", $string, $matches3);
        //var_dump($matches3[0]);
        //$lineBreakArray= preg_match_all("/\n/", $string, $matches4);
        //var_dump($matches4[0]);
        $errorArray= preg_match_all("/(\[[a-zA-Z]{3}\040[a-zA-Z]{3}\040\d{2}\040\d{2}:\d{2}:\d{2}\040\d{4}\])\040([\S]+)\040(\[client\040[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+\])([^\n]+)/", $string, $matches5);
        sort($matches5[4]);
        var_dump($matches5[4]);
        //var_dump($matches5[4]);
        /*$all= $matches5[0];
        $i=0;
        $error= array();
        while ($i<count($all)){
            preg_match("/[^\[]+$/", $string, $matches6);
            array_push($error, $matches6[0]);
            $i++;
        }
        var_dump($error);*/
        /*$e=array();
        $j=0;
        while($j<count($matches5[2])){
            if($matches5[2][$j] != '[error]'){
                array_push($e, $matches5[2][$j]);
            }
            $i++;
        }
        var_dump($e);*/
  	}

}
?>