<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//检测常量是否定义
class UserServiceLogic extends CI_Controller {
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Userservice_model','userservice');
		$this->load->helper('userservice');
		checksession();
	}

	//组织机构
	public function indexPage(){
		$this->load->view('admin/BaseData/UserService/UserServiceContainer');
	}


	//左边树形列表
	public function UserServiceLeft(){
		// 取出数据	
		$data['user'] = getAllUser();
	 	//$data['Role'] = AllRole();
	 	// echo $data['Role'];
	 	$this->load->view('admin/BaseData/UserService/UserServiceLeft',$data);
	}


	//默认打开页面
	public function Default1(){
		$this->load->view('admin/BaseData/UserService/Dedault');
	}


	//右边
	public function UserServiceMain(){

		$userid = $this->uri->segment(5);

		$data['userid'] = $userid;
		//传出id就好
		$data['service'] = getALLService();

		$data['userService'] = $this->userservice->selectServiceByUser($userid);

		$this->load->view('admin/BaseData/UserService/UserServiceMain',$data);
	}


	//保存
	public function setUserService(){
		$userid = $this->input->post('userid');
		$checkedId = $this->input->post('checkedId');

		$res = $this->userservice->updateUserService($userid,$checkedId);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

}