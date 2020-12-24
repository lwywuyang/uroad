<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》救援工单控制器类
 * 	     涉及到的表 - 
 * @author hwq
 * @date 2015-12-1
 */
class RescueOrderLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Rescueorder_model', 'rescue');
		checksession();
	}


	/**
	 * @desc   获取页面顶部下拉内容并展示页面
	 */
	public function indexPage(){
		$data['road'] = $this->rescue->selectAllRoad();
		$data['orderStatus'] = $this->rescue->selectOrderStatus();
		$this->load->view('admin/WXManage/RescueOrder/RescueOrderList',$data);
	}
	

	/**
	 * @desc   获取'爆料信息'页面信息
	 */
	public function onLoadRescueOrder(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by rescueid desc';
		}
		//road:road,orderType:orderType,orderNo:orderNo,startTime:startTime,endTime:endTime
		$road = $this->input->post('road');
		$orderStatus = $this->input->post('orderStatus');
		$orderNo = $this->input->post('orderNo');
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		
		//查询数据库
		$data = $this->rescue->selectRescueOrderMsg($road,$orderStatus,$orderNo,$startTime,$endTime,$pageOnload);

		foreach ($data['data'] as $k => $v) {
			$data['data'][$k]['picture'] = '<img onclick="checkPhoto(this.src)" src="'.$v['photourl'].'" class="photo-img">';
		}
		//$imgUrl = $this->config->item('img_url');
		ajax_success($data['data'],$data["pageOnload"]);
	}


	
}