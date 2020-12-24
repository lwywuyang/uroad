<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》车友报料控制器
 * 	     涉及到的表 - gde_eventuser
 * @author hwq
 * @version 1.0
 */
class ReportLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Report_model', 'report');
		checksession();
	}


	/**
	 * @desc   打开'信息发布'页面
	 */
	public function indexPage(){
		$data['eventtype'] = $this->report->selectEventType();
		
		$this->load->view('admin/MsgPublish/ReportList',$data);
	}
	

	/**
	 * @desc   获取'信息发布'页面信息
	 */
	public function onLoadReportMessage(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by eventid desc';
		}
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		$keyword = $this->input->post('keyword');
		$eventTypeSel = $this->input->post('eventTypeSel');
		$eventStatusSel = $this->input->post('eventStatusSel');
		//查询数据库
		$data=$this->report->selectReportMessage($startTime,$endTime,$keyword,$eventTypeSel,$eventStatusSel,$pageOnload);

		$imgUrl = 'http://hunangstapi.u-road.com/HuNanGSTAppAPIServer/';//报料图片保存前缀地址

		foreach($data['data'] as $k=>$v){
			switch ($v['status']) {
				case '1'://发布
					$data['data'][$k]['status'] = '发布';
					$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="changStatus(\''.$v['eventid'].'\',2)">取消发布</lable>';
					break;
				case '2'://待发布
					$data['data'][$k]['status'] = '待发布';
					$data['data'][$k]['operate'] = '<lable class="btn btn-primary btn-xs m-r-5" onclick="changStatus(\''.$v['eventid'].'\',1)">发布报料</lable><lable class="btn btn-danger btn-xs" onclick="changStatus(\''.$v['eventid'].'\',3)">打回</lable>';
					break;
				case '3'://打回
					$case = '';
					if ($v['case'] != '') {
						$case = '('.$v['case'].')';
					}
					$data['data'][$k]['status'] = '打回'.$case;
					//$data['data'][$k]['operate'] = '<lable class="btn btn-info btn-xs" onclick="changStatus(\''.$v['eventid'].'\',2)">审核通过</lable>';
					break;
				default:
					break;
			}

			$data['data'][$k]['upfile'] = '';
			if ($v['filepath'] != '') {
				$filePathArr = explode(',',$v['filepath']);
				foreach ($filePathArr as $kk => $vv) {
					$data['data'][$k]['upfile'] .= '<img src="'.$imgUrl.$vv.'" class="upfile" onclick="showLayerImage(this.src)">';
				}
			}
			
		}
		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function setEventStatus(){
		$eventid = $this->input->post('eventid');
		$status = $this->input->post('status');
		$case = $this->input->post('case');

		$res = $this->report->updateEventStatus($eventid,$status,$case);
		
		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

	/**
	 * [showCaseList 展示打回原因页面]
	 * @version 2016-05-03 1.0
	 * @return  [type]     [description]
	 */
	public function showCaseList(){
		$data['eventid'] = $this->input->get('eventid');

		$this->load->view('admin/MsgPublish/CaseList',$data);
	}
	
}