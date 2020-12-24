<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 事件审核-》事件审核
 * 	     涉及到的表 - gde-eventtraffic
 * @author hwq
 * @version 1.0
 */
class EventCheckLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Eventcheck_model', 'eventcheck');
		$this->load->model('Msgpublish/Roadevent_model', 'roadevent');
		checksession();
	}

	/**
	 * @desc   将秒数转换成X小时X分钟X秒的时间段格式
	 */
	private function secondToPeriod($second){
		$dayNum = floor($second/86400);
		$remainder1 = $second % 86400;//拿到余数
		$hourNum = floor($remainder1/3600);//每小时3600秒
		$remainder2 = $remainder1 % 3600;//再拿到余数
		$minuteNum = floor($remainder2/60);
		$secondNum = $remainder2 % 60;

		$return = '';
		if ($dayNum > 0) {
			$return .= $dayNum.'天 ';
		}
		if ($hourNum > 0) {
			$return .= $hourNum.'小时 ';
		}
		if ($minuteNum > 0) {
			$return .= $minuteNum.'分钟 ';
		}
		if ($secondNum > 0) {
			$return .= $secondNum.'秒';
		}

		return $return;
	}


	/**
	 * @desc   打开'事件审核'页面
	 */
	public function index(){
		$data['road'] = $this->roadevent->selectAllRoad();

		$this->load->view('admin/MsgPublish/EventCheck/EventCheckList',$data);
	}
	

	/**
	 * @desc   获取'事件审核'页面信息
	 */
	public function onLoadEventCheck(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			//$pageOnload['OrderDesc']='order by roadoldid asc,updatetime desc';
			$pageOnload['OrderDesc'] = 'order by intime';
		}

		$roadId = $this->input->post('roadId');
		$keyword = $this->input->post('keyword');
		//查询数据库
		$data=$this->eventcheck->selectInfoByParams($roadId,$keyword,$pageOnload);
		//$imgUrl = $this->config->item('img_url');//图片地址
		foreach($data['data'] as $k => $v){
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkInfo(\''.$v['eventid'].'\',\''.$v['eventtype'].'\')">查看</lable>';
			
			//国标图片
			$data['data'][$k]['newcode'] = '<img class="newcode-img" src="'.$v['picurl'].'">';

			if ($v['tag'] == '1') {
				$data['data'][$k]['reportout'] = '<span class="repeat">重复>></span>'.$v['reportout'];
			}

			$data['data'][$k]['duration'] = $this->secondToPeriod($v['duration']);
		}

		ajax_success($data['data'],$data["pageOnload"]);
	}



	/**
	 * @desc   '事件审核'->点击查看某事件详细,获取详细内容并展示事件详细页面
	 */
	public function showDetailMsg(){
		$eventid = $this->input->get('eventid');
		$eventtype = $this->input->get('eventtype');
		//$data['eventid'] = $eventid;
		//事件详细信息
		$data = $this->eventcheck->selectDetailMsgById($eventid);
		if ($eventtype != '1006005' && $eventtype != '1006007') {//突发和计划
			if (isset($data['directionno']))
				//起始站,结束站下拉框数据
				$data['alongStation'] = $this->eventcheck->selectAlongStation($eventid,$data['directionno']);
			else
				$data['alongStation'] = array();


			$this->load->view('admin/MsgPublish/EventCheck/EventCheckDetail',$data);
		}else{//收费站出入口事件
			$data['station'] = $this->eventcheck->selectStation($eventid);

			$this->load->view('admin/MsgPublish/EventCheck/EventControlCheckDetail',$data);
		}
		
	}

	/**
	 * @desc   '事件详细信息'页面->刷新页面或更改方向->重新获取站点地图数据
	 */
	public function onLoadRoadTableMsg(){
		$roadoldid = $this->input->post('roadoldid');
		$directionno = $this->input->post('direction');

		$data = $this->eventcheck->selectRoadTableMsg($roadoldid,$directionno);
		
		ajax_success($data,null);
	}


	
	/**
	 * @desc   '突发事件'->'事件详细'->发布事件信息
	 */
	public function changeStatus(){
		$eventid = $this->input->post('eventid');
		$status = $this->input->post('status');
		$eventstatus = $this->input->post('eventstatus');

		$EmplId = getsessionempid();
		$EmplName = getsessionempname();

		if(isEmpty($EmplId)){
			ajax_error('SESSION已丢失,无法执行当前操作!');return;
		}

		$res = $this->eventcheck->updateStatus($eventid,$status,$eventstatus,$EmplId,$EmplName);

		if ($res)
			ajax_success(true,null);
		else
			ajax_success(false,null);
	}


	/**
	 * @desc   '事件信息详情'页面->结束当前事件->更新数据库,保存新状态
	 */
	public function playOffTheInfo(){
		$eventid = $this->input->post('eventid');
		$realovertime = $this->input->post('realovertime');
		$operateId = getsessionempid();
		$operateName = getsessionempname();

		$res = $this->eventcheck->updateStatusToOff($eventid,$realovertime,$operateId,$operateName);
		if ($res)
			ajax_success(true,null);
		else
			ajax_success(false,null);
	}


	/**
	 * @desc   '事件信息'->新增事件
	 * @return [type]      [description]
	 */
	public function newEventMsg(){
		$eventType = $this->input->get('eventtype');//获取事件类型
		$data['thisEventType'] = $eventType;
		//拿到大类名称
		$data['eventtype'] = $this->eventcheck->selectEventType($eventType);
		//再拿细类
		$data['eventcause'] = $this->eventcheck->selectEventCause($eventType);
		//再拿高速公路
		$data['roadSel'] = $this->eventcheck->selectAllRoad();
		//交通状况单选框数据
		$data['roadTrafficColor'] = $this->eventcheck->selectRoadTrafficColor();

		$this->load->view('admin/MsgPublish/EventCheck/NewEventCheckList',$data);
	}

	/**
	 * @desc   '新增事件'->改变路段下拉框时,获取相应内容
	 */
	public function getNewRoadMsg(){
		$roadoldid = $this->input->post('roadoldid');
		//获取起始站和结束站的内容
		$data['alongStation'] = $this->eventcheck->selectStationByRoad($roadoldid);
		//获取路段的方向
		$data['direction'] = $this->eventcheck->selectDirectionByRoad($roadoldid);

		ajax_success($data,null);
	}


	/**
	 * @desc   '新增事件'->保存新增的事件信息
	 * @return [type]      [description]
	 */
	public function saveNewPushInfo(){
		$eventid = create_guid();
		$roadoldid = $this->input->post('roadoldid');
		$occtime = $this->input->post('occtime');
		$eventType = $this->input->post('eventType');
		$eventCause = $this->input->post('eventCause');
		$eventCauseName = $this->input->post('eventCauseName');
		$direction = $this->input->post('direction');
		$directionName = $this->input->post('directionName');
		$TrafficColor = $this->input->post('TrafficColor');
		$startStationId = $this->input->post('startStationid');
		$startStation = $this->input->post('startStation');
		$endStationId = $this->input->post('endStationid');
		$endStation = $this->input->post('endStation');
		$pushInfo = $this->input->post('pushInfo');

		$startStationArr = explode('(', $startStation);
		$startStationArr[1] = str_replace(')', '', $startStationArr[1]);
		$endStationArr = explode('(', $endStation);
		$endStationArr[1] = str_replace(')', '', $endStationArr[1]);

		$coor = $this->getAverageCoor($startStationId,$endStationId);

		$trafficSplitcode = $this->getTrafficSplitcode($startStationId,$endStationId);

		$EmplId = getsessionempid();
		$EmplName = getsessionempname();

		if(isEmpty($EmplId)){
			ajax_error('SESSION已丢失,无法执行当前操作!');return;
		}

		$res = $this->eventcheck->insertNewInfoMsg($eventid,$roadoldid,$occtime,$eventType,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStationArr,$endStationId,$endStationArr,$pushInfo,$EmplId,$EmplName,$coor,$trafficSplitcode);
		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}

	/**
	 * @desc   通过开始站点和结束站点的ID计算经纬度的平均值
	 * @return [type]      [description]
	 */
	public function getAverageCoor($startid,$endid){
		$coor_s = $this->eventcheck->selectCoor($startid);
		$coor_e = $this->eventcheck->selectCoor($endid);
		$average['x'] = ($coor_s['coor_x'] + $coor_s['coor_x'])/2;
		$average['y'] = ($coor_s['coor_y'] + $coor_s['coor_y'])/2;
		return $average;
	}


	/**
	 * @desc   查询stationcode并拼接
	 * @return [type]               [description]
	 */
	public function getTrafficSplitcode($startid,$endid){
		$trafficSplitcode = $this->eventcheck->selectTrafficSplitcode($startid,$endid);
		return $trafficSplitcode;
	}


	public function delEventMsg(){
		$deleteValue = $this->input->post('deleteValue');

		$deleteArr = explode(',',$deleteValue);
		$operateId = getsessionempid();
		$operateName = getsessionempname();

		$res = $this->eventcheck->deleteEventMsg($deleteArr,$operateId,$operateName);

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}

	public function showRealovertime(){
		$data['eventid'] = $this->input->get('eventid');
		$data['eventtype'] = $this->input->get('eventtype');

		$this->load->view('admin/MsgPublish/EventCheck/realovertimeList',$data);
	}

	public function changeControlStatus(){
		$eventid = $this->input->post('eventid');
		$status = $this->input->post('status');
		$eventstatus = $this->input->post('eventstatus');

		$res = $this->eventcheck->updateControlStatus($eventid,$status,$eventstatus);

		if ($res)
			ajax_success(true,null);
		else
			ajax_error($res);
	}
}