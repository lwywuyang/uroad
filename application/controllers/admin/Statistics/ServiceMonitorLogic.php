<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》多客服监控异常统计控制器类
 * @author hwq
 */
class ServiceMonitorLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Statistics/Servicemonitor_model', 'servicemonitor');
		checksession();
	}

	/**
	 * @desc   展示'多客服接入数统计'页面,默认展示的是路况模块内容
	 */
	public function indexPage(){
		$this->load->view('admin/Statistics/ServiceMonitorList');
	}
	

	/**
	 * @desc   '多客服接入数统计'->'菜单点击总数'->获取点击总数模块的内容并返回
	 *         点击总数模块为默认展示模块
	 */
	public function onLoadMsgInStatus(){
		//查询数据库
		$data = $this->servicemonitor->selectMsgInStatus();

		foreach ($data as $k => $v) {
			switch($v['status']){
				case '1':
					$data[$k]['status'] = '正常';break;
				case '0':
					$data[$k]['status'] = '<span class="red-font">异常</span>';break;
				default:
					$data[$k]['status'] = '';
			}
		}

		ajax_success($data,null);
	}

	public function onLoadMsgInHistory(){
		$pageOnload = page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ''){
			$pageOnload['OrderDesc'] = '';
		}
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$statusSel = $this->input->post('statusSel');
		//查询数据库
		$data = $this->servicemonitor->selectMsgInHistory($startTime,$endTime,$statusSel,$pageOnload);

		foreach ($data['data'] as $k => $v) {
			switch($v['status']){
				case '1':
					$data['data'][$k]['status'] = '正常';break;
				case '0':
					$data['data'][$k]['status'] = '<span class="red-font">异常</span>';break;
				default:
					$data['data'][$k]['status'] = '';
			}
		}

		ajax_success($data['data'],$data['PagerOnload']);
	}

}