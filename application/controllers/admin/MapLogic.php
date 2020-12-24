<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class MapLogic extends CI_Controller{
	public function selectXYPage()
	{ 	
		$data["x"]=$this->input->get("x");
		$data["y"]=$this->input->get("y");
		
		$this->load->view('admin/SelectXYMap2',$data);
	}

	public function selectXYPage1()
	{ 	
		$data["x"]=$this->input->get("x");
		$data["y"]=$this->input->get("y");
		
		$this->load->view('admin/SelectXYMap3',$data);
	}
}