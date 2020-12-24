<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 统计分析-》微信菜单点击量控制器类
 * 	     主要涉及到的表 - 
 * @author hwq
 * @date 2015-12-7
 * @version 1.0
 */
class PopularizeLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Statistics/Popularize_model', 'promotion');
		checksession();
		//$this->load->helper('network');
	}


	/**
	 * @desc   打开'微信菜单点击量'页面
	 */
	public function index(){
		//$data['road'] = $this->event->selectRoadMsg();
		$this->load->view('admin/Statistics/PopularizeList');
	}

	/**
	 * @desc   查询微信菜单点击量总览数据
	 * @return [type]      [description]
	 */
	public function onLoadPopularizeStatistics(){
		$pageOnload = page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc'] == ""){
			$pageOnload['OrderDesc'] = '';
		}
		$StartTime = $this->input->post('StartTime');
		$EndTime = $this->input->post('EndTime');
		$keyword = $this->input->post('keyword');

		$data = $this->promotion->selectPopularizeStatistics($keyword,$StartTime,$EndTime,$pageOnload);

		ajax_success($data['data'],$data['pageOnload']);
	}

}