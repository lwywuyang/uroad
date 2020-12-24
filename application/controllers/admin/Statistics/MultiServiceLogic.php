<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》多客服统计控制器类
 * 	     主要的表 - 
 * @author hwq
 */
class MultiServiceLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Statistics/Multiservice_model', 'multiservice');
		checksession();
	}

	/**
	 * @desc   展示'多客服接入数统计'页面,默认展示的是路况模块内容
	 */
	public function indexPage(){
		$this->load->view('admin/Statistics/MultiServiceList');
	}
	

	/**
	 * @desc   '多客服接入数统计'->'菜单点击总数'->获取点击总数模块的内容并返回
	 *         点击总数模块为默认展示模块
	 */
	public function onLoadMsgByDate(){
		//查询数据库
		$data = $this->multiservice->selectMsgByDate();

		ajax_success($data,null);
	}

	public function onLoadMsgByService(){
		//查询数据库
		$data = $this->multiservice->selectMsgByService();

		ajax_success($data,null);
	}

}