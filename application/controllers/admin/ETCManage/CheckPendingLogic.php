<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》突发事件,计划事件控制器
 * 	     涉及到的表 - gde_eventtraffic,gde_roadold
 * @author hwq
 * @date 2015-11-17
 * @version 1.0
 */
class CheckPendingLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Etcmanage/Checkpending_model', 'Checkpending');
		checksession();
	}


	/**
	 * @desc   打开'信息发布'页面
	 */
	public function indexPage(){
//		$data['eventtype'] = $this->input->get('eventtype');
//		$data['road'] = $this->roadevent->selectAllRoad();
		
		$this->load->view('admin/ETCManage/CheckPending/CheckPendingList');
	}
	

	/**
	 * @desc   获取'信息发布'页面信息
	 */
	public function onLoadCheckPendingEvent(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			$pageOnload['OrderDesc']='order by id desc';
		}
		$roadId = $this->input->post('roadId');
		$eventType = $this->input->post('eventType');
		$status = $this->input->post('status');
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data=$this->Checkpending->selectInfoByParams($eventType,$roadId,$status,$keyword,$pageOnload);

		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs m-5" onclick="checkInfo(\''.$data['data'][$k]['cardno'].'\',\''.$data['data'][$k]['carno'].'\')">新 增</lable>';
			$data['data'][$k]['operate'] .= '<lable class="btn btn-danger btn-xs m-5" onclick="delCheckPending(\''.$data['data'][$k]['id'].'\')">审核不通过</lable>';
			//将中文的括号替换成英文括号,防治影响换行
			//$data['data'][$k]['name'] = $this->strReplace($v['name']);
			//$data['data'][$k]['name'] = str_replace('（）', '(', $v['name']);
		}
		ajax_success($data['data'],$data["pageOnload"]);
	}


	public function setTopStatus(){
		$eventid = $this->input->post('eventid');
		$toptag = $this->input->post('toptag');

		$res = $this->roadevent->updateTopStatus($eventid,$toptag);
		
		if ($res === true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}



	/**
	 * @desc   '信息发布'->点击查看某事件详细,获取详细内容并展示事件详细页面
	 */
	public function showDetailMsg(){
//		$data['eventType'] = $this->input->get('eventtype');
		$data['cardno'] = $this->input->get('cardno');
		$data['carno'] = $this->input->get('carno');


//		if ($data['eventId'] == '0') {//新增
//			$data['alongStation'] = array('direction1_json'=>'[{"poiid":"","name":""}]','direction2_json'=>'[{"poiid":"","name":""}]');
//			$data['stationcode'] = '[{"poiid":"","stationcode":""}]';
//		}else{//修改
			//事件详细信息
			$data['data'] = $this->Checkpending->selectDetailMsgById($data['cardno']);
		if(empty($data['data'])){
			$data['data'][0]['cardid'] = $data['cardno'];
			$data['data'][0]['numberplate'] = $data['carno'];
			$data['type']= 'add';
		}
			//起始站,结束站下拉框数据
//			$data['alongStation'] = $this->roadevent->selectAlongStation($data['eventId']);
//
//
//			$stationcodeArr = $this->roadevent->selectStationCode($data['eventId']);
//			$data['stationcode'] = json_encode($stationcodeArr);
			//$data['stationcode'] = $this->roadevent->selectStationCode($data['eventId']);
//		}
		//所有路段->用于下拉框内容
//		$data['roadSel'] = $this->roadevent->selectAllRoad();
//		$data['eventTypeSel'] = $this->roadevent->selectEventType($data['eventType']);
//		$data['eventCauseSel'] = $this->roadevent->selectEventCause($data['eventType']);
//		//交通状况单选框数据
//		$data['roadTrafficColor'] = $this->roadevent->selectRoadTrafficColor();
		
		$this->load->view('admin/ETCManage/CheckPending/CheckPendingDetailList',$data);
	}

	/**
	 * 新增保存
	 */

	public function saveNewCheckPendingMsg(){
		$content = $this->input->post('content');
		$content['intime'] = date('Y-m-d H:i:s');
		$content['operatorname'] = getsessionempname();
		$data = $this->Checkpending->insertRoadPoiMsg($content);
		ajax_success($data,null);
	}

	/**
	 * 新增修改
	 */
	public function saveCheckPendingMsg(){
		$cardno = $this->input->post('cardno');
		$content = $this->input->post('content');
		$content['cardid'] = $cardno;
		$content['intime'] = date('Y-m-d H:i:s');
		$content['operatorname'] = getsessionempname();
		$data = $this->Checkpending->updateRoadPoiMsg($cardno,$content);

		ajax_success($data,null);
	}

	/**
	 * @desc   '事件详细信息'页面->刷新页面或更改方向->重新获取站点地图数据
	 * @data   2015-10-12 15:46:58
	 */
	public function onLoadRoadTableMsg(){
		$roadoldid = $this->input->post('roadoldid');
		$directionno = $this->input->post('direction');
		//var_dump($roadoldid,$directionno);
		$data = $this->roadevent->selectRoadTableMsg($roadoldid,$directionno);
		//var_dump($data);exit;
		foreach ($data as $k => $v) {
			//将中文的括号替换成英文括号,防治影响换行
			$data[$k]['name'] = $this->strReplace($v['name']);
		}
		
		ajax_success($data,null);
	}



	/**
	 * @desc   将中文的括号替换成英文括号
	 * @param  [type]      $str [description]
	 * @return [type]           [description]
	 */
	private function strReplace($str){
		$str_r = str_replace('（', '(', $str);
		$str_r2 = str_replace('）', ')', $str_r);
		return $str_r2;
	}



	/**
	 * @desc   '事件信息详情'页面->结束当前事件->更新数据库,保存新状态
	 */
	public function playOffTheInfo(){
		$eventid = $this->input->post('eventid');
		$operateId = getsessionempid();
		$operateName = getsessionempname();

		$res = $this->roadevent->updateStatusToOff($eventid,$operateId,$operateName);
		if ($res)
			ajax_success(true,null);
		else
			ajax_success(false,null);
	}



	/**
	 * @desc   '新增事件'->改变路段下拉框时,获取相应内容
	 */
	public function getNewRoadMsg(){
		$roadoldid = $this->input->post('roadoldid');
		//获取起始站和结束站的内容
		$data['alongStation'] = $this->roadevent->selectStationByRoad($roadoldid);
		//获取路段的方向
		$data['direction'] = $this->roadevent->selectDirectionByRoad($roadoldid);
		$data['stationcode'] = $this->roadevent->selectStationcodeByRoad($roadoldid);
		//var_dump($data['stationcode']);exit;
		ajax_success($data,null);
	}



	/**
	 * @desc   '事件'->'事件详细'->修改后,提交审核->提交发布事件的审核
	 */
	public function savePushInfo(){
		$eventId = $this->input->post('eventId');
		$eventType = $this->input->post('eventType');
		$roadoldid = $this->input->post('roadoldid');
		$occtime = $this->input->post('occtime');
		$planOverTime = $this->input->post('planOverTime');
		$eventCause = $this->input->post('eventCause');
		$eventCauseName = $this->input->post('eventCauseName');
		$direction = $this->input->post('direction');
		$directionName = $this->input->post('directionName');
		$TrafficColor = $this->input->post('TrafficColor');
		$startStationId = $this->input->post('startStationid');
		$startStation = $this->input->post('startStation');
		$endStationId = $this->input->post('endStationid');
		$endStation = $this->input->post('endStation');
		$startStake = $this->input->post('startStake');
		$endStake = $this->input->post('endStake');
		$title = $this->input->post('title');
		$pushInfo = $this->input->post('pushInfo');

		//计算事发经纬度和trafficsplitcode
		//var_dump($startStationId,$endStationId);exit;
		$coorAndSplitCode = $this->getCoorAndSplitCode($startStationId,$endStationId);

		$EmplId = getsessionempid();
		$EmplName = getsessionempname();
		if(isEmpty($EmplId)){
			ajax_error('SESSION已丢失,无法执行当前操作!');return;
		}


		if ($eventId == '0') {//新增
			$eventId = create_guid();
			$res = $this->roadevent->insertToSavePushInfo($eventId,$eventType,$roadoldid,$occtime,$planOverTime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$startStake,$endStake,$title,$pushInfo,$coorAndSplitCode,$EmplId,$EmplName);
		}else{//修改
			$res = $this->roadevent->updateToSavePushInfo($eventId,$eventType,$roadoldid,$occtime,$planOverTime,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStation,$endStationId,$endStation,$startStake,$endStake,$title,$pushInfo,$coorAndSplitCode,$EmplId,$EmplName);
		}
		
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('更新数据库失败!');
	}


	/**
	 * @desc   根据起始站和结束站估算事发地点的经纬度
	 * @param  [type]      $startStationId [description]
	 * @param  [type]      $endStationId   [description]
	 * @return [type]                      [description]
	 */
	private function getCoorAndSplitCode($startStationId,$endStationId){
		$startCoor = $this->roadevent->selectCoor($startStationId);
		$endCoor = $this->roadevent->selectCoor($endStationId);
		$result['x'] = ($startCoor['coor_x']+$endCoor['coor_x'])/2;
		$result['y'] = ($startCoor['coor_y']+$endCoor['coor_y'])/2;

		$startSplitCode = $this->roadevent->selectSplitCode($startStationId);
		$endSplitCode = $this->roadevent->selectSplitCode($endStationId);
		$result['splitCode'] = $startSplitCode['stationcode'].$endSplitCode['stationcode'];

		return $result;
	}

	/**
	 * @desc   事件列表->结束选择的事件信息
	 * @return [type]      [description]
	 */
	public function delEventMsg(){
		$deleteValue = $this->input->post('deleteValue');
		//var_dump($deleteValue);exit;
		$deleteArr = explode(',',$deleteValue);
		$operateId = getsessionempid();
		$operateName = getsessionempname();
		$res = $this->roadevent->deleteEventMsg($deleteArr,$operateId,$operateName);
		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_error('操作数据库失败!');
		}
	}

	/**
	 * 删除审核不通过记录
	 */
	public function delCheckPending(){
		$id = $this->input->post('id');
		$res = $this->Checkpending->delCheckPending($id);
		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_error('操作失败!');
		}
	}

	


	//取消发布
	/*public function cancelPushMsg(){
		$eventid = $this->input->post('eventid');
		$EmplId = getsessionempid();
		$EmplName = getsessionempname();
		$res = $this->roadevent->updateToCancelPushInfo($eventid,$EmplId,$EmplName);
		if ($res)
			ajax_success(true,null);
		else
			ajax_error('取消发布失败!');
	}
*/
	/**
	 * 调用存储过程
	 */
	public function updateCheckPending(){
		$ids = $this->input->post('values');
		echo json_encode($ids);
	}
	
}