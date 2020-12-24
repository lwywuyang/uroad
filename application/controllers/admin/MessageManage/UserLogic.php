<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息管理-》用户管理控制器类
 * 	     涉及到的表 - gde_user
 * @author hwq
 * @version 1.0
 */
class UserLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('MessageManage/User_model', 'user');
		checksession();
	}


	/**
	 * @desc   打开'用户管理'页面
	 */
	public function indexPage(){

		$data['eventtype'] = $this->user->selectEventTypeMsg();

		$this->load->view('admin/MessageManage/User/UserList',$data);
	}
	

	/**
	 * @desc   获取'用户管理'页面信息
	 */
	public function onLoadUser(){
		$pageOnload = page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ""){
			$pageOnload['OrderDesc'] = '';
		}
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$keyword = $this->input->post('keyword');
		//$typeSel = $this->input->post('typeSel');
		//查询数据库
		$data = $this->user->selectUserMsg($startTime,$endTime,$keyword,$pageOnload);

		//$imgUrl = 'http://app.zjzhgs.com/GaoSuTongZJNew/';
		foreach($data['data'] as $k=>$v){
			//用户名
			if ($v['username'] == '') {
				$data['data'][$k]['username'] = '匿名用户';
			}

			//现场照片
			if ($v['iconfile'] != '') {
				$data['data'][$k]['iconfile'] = '<img class="photo" onclick="showLayerImageJs(this.src)" src="'.$imgUrl.$v['filename'].'">';
			}
		}
		ajax_success($data['data'],$data["pageOnload"]);
	}


}