<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 	@desc 	基础数据->服务区图集控制器
 * 	       	涉及到的表:gde-welcomejpg
 * 	@author hwq
 */

class ServiceImagesLogic extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('baseData/Serviceimages_model', 'serviceimages');
		checksession();
	}


	public function indexPage(){
		$this->load->view('admin/BaseData/Welcome/WelcomeList');
	}


	public function onLoadWelcomeMsg(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']=="")
		{
			$pageOnload['OrderDesc']='order by id desc';
		}
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		
		$data = $this->serviceimages->selectWelcomeMsg($startTime,$endTime,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['picImg']='<img class="welcome-pic" onclick="showLayerImageJs(this.src)" src="'.$v['url'].'">';
			$data['data'][$k]['operate']='<lable class="btn btn-success btn-xs" onclick="checkWelcome('.$v['id'].')">查看</lable>';
		}
		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function addOrCheckWelcomeMsg(){
		$data['id'] = $this->input->get('id');
		if ($data['id'] == '0') {//新增
			# code...
		}else{
			//var_dump($data);exit;
			$data['data'] = $this->serviceimages->selectWelcomeMsgById($data['id']);
		}

		$this->load->view('admin/BaseData/Welcome/addOrCheckWelcomeMsgList',$data);
	}


	public function saveWelcomeMsg(){
		$id = $this->input->post('id');
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$imgurl = $this->input->post('imgurl');

		if ($id == '0')//新增
			$res = $this->serviceimages->insertWelcomeMsg($startTime,$endTime,$imgurl);
		else//修改
			$res = $this->serviceimages->updateWelcomeMsg($id,$startTime,$endTime,$imgurl);
		
		//var_dump($this->db->last_query());exit;
		if($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作失败!');
	}


	public function delWelcomeMsg(){
		$delValue = $this->input->post('deleteValue');

		$res = $this->serviceimages->deleteWelcomeMsg($delValue);

		if($res)
			ajax_success(true,null);
		else
			ajax_error('数据库操作失败!');
	}


}