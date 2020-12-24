<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 微信管理-》爆料信息控制器类
 * 	     涉及到的表 - gde-eventtraffic
 * @author hwq
 * @date 2015-10-26
 * @version 1.0
 */
class ReportMessageLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 * @date 2015-10-9 17:45:38
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('WXManage/Reportmessage_model', 'report');
		checksession();
	}


	/**
	 * @desc   打开'爆料信息'页面
	 * @data   2015-10-9 17:47:24
	 */
	public function indexPage(){
		$data['eventtype'] = $this->report->selectEventTypeMsg();
		//$data['road'] = $this->roadevent->selectAllRoad();
		$this->load->view('admin/WXManage/ReportMessage/ReportMessageList',$data);
	}
	

	/**
	 * @desc   获取'爆料信息'页面信息
	 * @data   2015-10-9 17:54:38
	 */
	public function onLoadReportMsg(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by occtime desc';
		}
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$keyword = $this->input->post('keyword');
		$typeSel = $this->input->post('typeSel');
		//查询数据库
		$data = $this->report->selectReportMsg($startTime,$endTime,$keyword,$typeSel,$pageOnload);

		//$imgUrl = $this->config->item('img_url');
		$url = 'http://hubeiweixin.u-road.com';
		foreach($data['data'] as $k=>$v){
			//$data['data'][$k]['operate'] = '<a onclick="checkPhoto(\''.$url.$v['filename'].'\')">查看图片</a>';
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkPhoto('.$url.$v['filename'].')">查看图片</lable>';
			//现场照片
			$data['data'][$k]['photo'] = '<img class="photo-img" src="'.$url.$v['filename'].'">';
		}
		ajax_success($data['data'],$data["pageOnload"]);
	}


	
}