<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class ShowimgLogic extends CI_Controller {

	public function index()
	{ 	
	
		 
		$data['imgurl']=$this->input->get('imgurl');
		
		   
		$this->load->view('admin/Showimg',$data);

	}
}









