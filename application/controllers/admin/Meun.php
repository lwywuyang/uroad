<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class meun extends CI_Controller {
	public function index()
	{ 	
		// 验证session是否存在，不存在就返回登陆页面
	 	$this->load->library('session');
	 	$data=array(
			"EmpID"=>getsessionempid(),
			"EmplName"=>getsessionempname(),
			"DepaName"=>getsessiondepaname()
			);
	 	// 按照员工id查出功能权限
	  	$this->load->model('Organization/empper_model', 'empper'); 
	  	$this->load->model('Organization/function_model', 'fun');
	  	//取出顶级功能
	  	//取出事件管理
	  	$data['fundata']=TopEmpPerMenu($data['EmpID']);	 

		$this->load->view('admin/index',$data);
	}
}
