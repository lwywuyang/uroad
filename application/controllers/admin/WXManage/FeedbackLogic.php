<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》意见反馈控制器类
 * 	     涉及到的表 - 
 * @author hwq
 * @date 2015-11-9
 * @version 1.0
 */
class FeedbackLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Feedback_model', 'feedback');
		checksession();
	}


	/**
	 * @desc   打开'意见反馈'页面
	 */
	public function indexPage(){
		$this->load->view('admin/WXManage/Feedback/FeedbackList');
	}
	

	/**
	 * @desc   获取'爆料信息'页面信息
	 * @data   2015-10-9 17:54:38
	 */
	public function onLoadFeedbackMsg(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by intime desc';
		}
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data = $this->feedback->selectFeedbackMsg($startTime,$endTime,$keyword,$pageOnload);

		//$imgUrl = $this->config->item('img_url');
		ajax_success($data['data'],$data["pageOnload"]);
	}


	
}