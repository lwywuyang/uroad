<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》易堵预报的控制器类
 * 	     涉及到的表 - gde-eventtraffic
 * @author hwq
 * @version 1.0
 */
class EventTipsLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Eventtips_model', 'tips');
		$this->load->model('Msgpublish/Roadevent_model', 'roadevent');
		checksession();
	}


	/**
	 * @desc   打开'信息发布'页面
	 */
	public function indexPage(){
		//事件类型
		$data['eventtype'] = $this->input->get('eventtype');
		$data['road'] = $this->roadevent->selectAllRoad();

		$this->load->view('admin/MsgPublish/EventTips/EventTipsList',$data);
	}
	

	/**
	 * @desc   获取'信息发布'页面信息
	 */
	public function onLoadEventTips(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by updatetime desc';
		}
		//var_dump($pageOnload);exit;
		$roadId = $this->input->post('roadId');
		$eventType = $this->input->post('eventType');
		$status = $this->input->post('status');
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data=$this->tips->selectTipsByParams($eventType,$roadId,$status,$keyword,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkInfo(\''.$data['data'][$k]['eventid'].'\')">查看</lable>';
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function checkTipsMsg(){
		$data['eventid'] = $this->input->get('eventid');

		if ($data['eventid'] != '0') {//修改
			$data['data'] = $this->tips->selectTipMsgById($data['eventid']);
		}

		$data['road'] = $this->roadevent->selectAllRoad();

		$this->load->view('admin/MsgPublish/EventTips/TipsDetailMsgList',$data);
	}


	public function saveEventTips(){

		$eventid = $this->input->post('eventid');
		$roadSel = $this->input->post('roadSel');
		$occtime = $this->input->post('occtime');
		$tipContent = $this->input->post('tipContent');

		$EmplId = getsessionempid();
		$EmplName = getsessionempname();

		if ($eventid == '0'){//新增
			$eventid = create_guid();
			$res = $this->tips->insertNewTips($eventid,$roadSel,$occtime,$tipContent,$EmplId,$EmplName);
		}else{//修改
			$res = $this->tips->updateTips($eventid,$roadSel,$occtime,$tipContent,$EmplId,$EmplName);
		}

		if ($res == true){
			//调用接口
			/*$apiUrl = 'http://02712122.com/HuBeiQyWeChatAPIServer/index.php/wechatmsgserver/sendsummarypush';
			network_post($apiUrl,array());*/
			ajax_success(true,null);
		}else{
			ajax_error($res);
		}
	}


	public function delTipsMsg(){
		$deleteValue = $this->input->post('deleteValue');

		$deleteArr = explode(',',$deleteValue);
		$res = $this->tips->deleteTipsMsg($deleteArr);

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}

}