<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》用户数统计控制器类
 * 	     主要涉及到的表 - 
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class UserNumLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Statistics/Usernum_model', 'usernum');
		checksession();
		//$this->load->helper('network');
	}


	/**
	 * @desc   打开'用户数统计'页面
	 */
	public function index(){
		//$data['road'] = $this->event->selectRoadMsg();
		$this->load->view('admin/Statistics/UserNumList');
	}

	/**
	 * @desc   查询用户数统计总览数据
	 * @return [type]      [description]
	 */
	public function onLoadUserNumMsg(){

		$data = $this->usernum->selectUserNumMsg();

		//var_dump($data);exit;
		ajax_success($data,null);
	}

	/**
	 * @desc   获取APP用户趋势页面的图表数据
	 */
	public function onLoadDateStatisticsMsg(){
		$DateStartTime = $this->input->post('DateStartTime');
		$DateEndTime = $this->input->post('DateEndTime');
		/*if (!isEmpty($AppStartTime))
			$AppStartTime .= ' 00:00:00';
		if (!isEmpty($AppEndTime))
			$AppEndTime .= ' 23:59:59';*/

		$data['line'] = $this->usernum->selectDateStatisticsMsg($DateStartTime,$DateEndTime);
		//
		//组装遍历数组用的数组
		$table = array();
		foreach ($data['line']['AndroidDate'] as $k => $v) {
			$table[$k]['Date'] = $v;
		}
		foreach ($data['line']['AndroidIncrease'] as $k => $v) {
			$table[$k]['AndroidIncrease'] = $v;
		}
		foreach ($data['line']['IOSIncrease'] as $k => $v) {
			$table[$k]['IOSIncrease'] = $v;
		}
		$data['tableData'] = array_reverse($table);

		ajax_success($data,null);
	}

}