<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc 信息发布-》突发事件,计划事件,管制事件,出行提示,实时路况共同的控制器类
 * 	     涉及到的表 - gde-eventtraffic
 * @author hwq
 * @version 1.0
 */
class RoadEventLogic extends CI_Controller {
	/**
	 * @desc 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('Msgpublish/Roadevent_model', 'roadevent');
		$this->load->model('baseData/Roadpoi_model', 'roadpoi');
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
	 * [reTrueTime 处理各种时间内容，将异常时间去掉]
	 * @version 2016-11-30 1.0
	 * @param   [string]     $time [数据库查询得到的时间]
	 * @return  [string]           [正确的时间]
	 */
	private function reTrueTime($time){
		if (substr($time, 0, 4) == '0000')
			return '';
		else
			return $time;
	}

	public function checkRescue($eventType){
		$this->load->helper('empper');
		$EmplId = getsessionempid();
		switch ($eventType) {
			case '1006001'://突发事件
				$hasRescue = GetUserHasFunPermission($EmplId,'N1_1_1')===true?1:0;
				break;
			case '1006002'://计划事件
				$hasRescue = GetUserHasFunPermission($EmplId,'N1_2_1')===true?1:0;
				break;
			case '1006005'://管制事件(突发)
				$hasRescue = GetUserHasFunPermission($EmplId,'N1_8_1')===true?1:0;
				break;
			case '1006007'://管制事件(计划)
				$hasRescue = GetUserHasFunPermission($EmplId,'N1_8_1')===true?1:0;
				break;
			default:
				break;
		}
		return $hasRescue;
	}


	/**
	 * @desc   打开'信息发布'页面
	 */
	public function indexPage(){
		//获取下拉框内容
		$data['roadper'] = $this->roadpoi->selectAllRoadPer();

		//高德触发的数据↓
		$data['ids'] = $this->input->get('ids');
		$data['gaodecztype'] = $this->input->get('gaodecztype');//创建工单还是查看  1是创建 2是查看
		
		$roadold['0'] = $this->roadpoi->selectAllRoad();
		foreach($data['roadper'] as $k => $v){
			$roadold[$v['id']] = $this->roadpoi->selectRoadInPer((string)$v['roadoldids']);
		}

		$roadoldid = array();$roadname = array();
		foreach($roadold as $k=>$v){
			if($v == null){
				$roadoldid[$k] = array();
				$roadname[$k] = array();
			}else{
				foreach($v as $kk=>$vv){
					$roadoldid[$k][$kk] = $vv['roadoldid'];
					$roadname[$k][$kk] = $vv['shortname'];
				}
			}
		}
		$data['roadoldidArr'] = json_encode($roadoldid);
		$data['roadoldnameArr'] = json_encode($roadname);

		$data['eventtype'] = $this->input->get('eventtype');
		$data['road'] = $this->roadpoi->selectAllRoad();
		$data['hasRescue'] = $this->checkRescue($data['eventtype']);

		$this->load->view('admin/MsgPublish/RoadEvent/RoadEventList',$data);
	}
	

	/**
	 * @desc   获取'信息发布'页面信息
	 */
	public function onLoadRoadEvent(){
		$pageOnload=page_onload();
		// 判断排序是否存在
		if($pageOnload['OrderDesc']==""){
			//$pageOnload['OrderDesc']='order by roadoldid asc,updatetime desc';
			$pageOnload['OrderDesc']='order by occtime desc';
		}

		$roadId = $this->input->post('roadId');
		$eventType = $this->input->post('eventType');
		$status = $this->input->post('status');
		$keyword = $this->input->post('keyword');
		$startTime = $this->input->post('startTime');
		$endTime = $this->input->post('endTime');
		//查询数据库
		$data=$this->roadevent->selectInfoByParams($roadId,$eventType,$status,$keyword,$startTime,$endTime,$pageOnload);

		//$imgUrl = $this->config->item('img_url');//图片地址
		foreach($data['data'] as $k=>$v){
			$data['data'][$k]['occtime'] = $this->reTrueTime($v['occtime']);
			$data['data'][$k]['checktime'] = $this->reTrueTime($v['checktime']);
			$data['data'][$k]['updatetime'] = $this->reTrueTime($v['updatetime']);
			$data['data'][$k]['canceltime'] = $this->reTrueTime($v['canceltime']);
			$data['data'][$k]['readtime'] = $this->reTrueTime($v['readtime']);
			//$data['data'][$k]['operate'] = '<a onclick="checkInfo(\''.$data['data'][$k]['eventid'].'\')">查看</a>';
			$data['data'][$k]['operate'] = '<lable class="btn btn-success btn-xs" onclick="checkInfo(\''.$data['data'][$k]['eventid'].'\')">查看</lable>';
			
			//国标图片
			$data['data'][$k]['newcode'] = '<img class="newcode-img" src="'.$v['picurl'].'">';
		}
//var_dump($data['data']);exit;
		ajax_success($data['data'],$data["pageOnload"]);
	}


	/**
	 * @desc   '信息发布'->操作信息是否置顶
	 */
	public function operateTop(){
		$eventid = $this->input->post('eventid');
		$top = $this->input->post('top');
		$res = $this->roadevent->updateEventTraffic($eventid,$top);
		//var_dump($res);exit;
		if ($res === true) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}


	/**
	 * @desc   '信息发布'->点击'简图'->获取简图路径
	 */
	public function getSmallPic(){
		$data = $this->roadevent->selectSmallPic();

		ajax_success($data[0]['filename'],null);
	}



	/**
	 * @desc   '信息发布'->点击查看某事件详细,获取详细内容并展示事件详细页面
	 */
	public function showDetailMsg(){
		$eventid = $this->input->get('eventid');
		$eventType = $this->input->get('eventtype');
		//权限
		$data['hasRescue'] = $this->checkRescue($eventType);

		$data['eventid'] = $eventid;

		//管理处和路段下拉框联动
		/*$data['roadper'] = $this->roadpoi->selectAllRoadPer();

		$roadold['0'] = $this->roadpoi->selectAllRoad();
		foreach($data['roadper'] as $k => $v){
			$roadold[$v['id']] = $this->roadpoi->selectRoadInPer((string)$v['roadoldids']);
		}

		$roadoldid = array();$roadname = array();
		foreach($roadold as $k=>$v){
			if($v == null){
				$roadoldid[$k] = array();
				$roadname[$k] = array();
			}else{
				foreach($v as $kk=>$vv){
					$roadoldid[$k][$kk] = $vv['roadoldid'];
					$roadname[$k][$kk] = $vv['shortname'];
				}
			}
		}
		$data['roadoldidArr'] = json_encode($roadoldid);
		$data['roadoldnameArr'] = json_encode($roadname);*/
		//所有路段->用于下拉框内容
		$data['roadSel'] = $this->roadevent->selectAllRoad();
		//大类下拉框数据
		$data['eventTypeSel'] = $this->roadevent->selectAllEventType();

		//小类下拉框数据
		foreach($data['eventTypeSel'] as $v){
            $eventCauseSel[$v['dictcode']] = $this->roadevent->selectEventCause($v['dictcode']);
        }
        $eventCauseId = array();$eventCauseName = array();
        foreach($eventCauseSel as $k=>$v){
            if($v == null){
                $eventCauseId[$k] = array();
                $eventCauseName[$k] = array();
            }else{
                foreach($v as $kk=>$vv){
                    $eventCauseId[$k][$kk] = $vv['dictcode'];
                    $eventCauseName[$k][$kk] = $vv['name'];
                }
            }
        }
        $data['eventCauseId_json'] = json_encode($eventCauseId);
        $data['eventCauseName_json'] = json_encode($eventCauseName);

		//$data['eventCauseSel'] = $this->roadevent->selectAllEventCause();
		//交通状况单选框数据
		$data['roadTrafficColor'] = $this->roadevent->selectRoadTrafficColor();
		//起始站,结束站下拉框数据
		$data['alongStation'] = $this->roadevent->selectAlongStation($eventid);

		//事件详细信息
		$data['data'] = $this->roadevent->selectDetailMsgById($eventid);
		//计算持续时间
		$data['duration'] = $this->secondToPeriod($data['data'][0]['duration']);

		$this->load->view('admin/MsgPublish/RoadEvent/RoadEventDetailList',$data);
	}

	/**
	 * @desc   '事件详细信息'页面->刷新页面或更改方向->重新获取站点地图数据
	 */
	public function onLoadRoadTableMsg(){
		$roadoldid = $this->input->post('roadoldid');
		$directionno = $this->input->post('direction');

		$data = $this->roadevent->selectRoadTableMsg($roadoldid,$directionno);
		
		ajax_success($data,null);
	}


	
	/**
	 * @desc   '突发事件'->'事件详细'->发布/保存事件信息
	 */
	public function savePushInfo(){
		$eventid = $this->input->post('eventid');
		$roadoldid = $this->input->post('roadoldid');
		$occtime = $this->input->post('occtime');
		$planovertime = $this->input->post('planovertime');
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

		//标记发布1和保存0
		$tag = $this->input->post('tag');

		preg_match('/\(K(.*)\)/', $startStation,$pregRes);
		$startStationArr = array($startStation,$startStation);
		if (!empty($pregRes[0])) {
			$startStationArr[0] = str_replace($pregRes[0], '', $startStation);
			$startStationArr[1] = 'K'.$pregRes[1];
		}

		//end
		preg_match('/\(K(.*)\)/', $endStation,$pregRes2);
		$endStationArr = array($endStation,$endStation);
		if (!empty($pregRes2[0])) {
			$endStationArr[0] = str_replace($pregRes2[0], '', $endStation);
			$endStationArr[1] = 'K'.$pregRes2[1];
		}

		/*$startStationArr = explode('(', $startStation);
		$startStationArr[1] = str_replace(')', '', $startStationArr[1]);
		$endStationArr = explode('(', $endStation);
		$endStationArr[1] = str_replace(')', '', $endStationArr[1]);*/

		$coor = $this->getAverageCoor($startStationId,$endStationId);
		$trafficSplitcode = $this->getTrafficSplitcode($startStationId,$endStationId);

		$EmplId = getsessionempid();
		$EmplName = getsessionempname();
		if(isEmpty($EmplId)){
			ajax_error('SESSION已丢失,无法执行当前操作!');return;
		}

		$res = $this->roadevent->updateToSavePushInfo($eventid,$roadoldid,$occtime,$planovertime,$eventType,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStationArr,$endStationId,$endStationArr,$pushInfo,$EmplId,$EmplName,$coor,$trafficSplitcode,$tag);
		if ($res)
			ajax_success(true,null);
		else
			ajax_success(false,null);
	}

	public function sendEventback(){
		$eventid = $this->input->post('eventid');

		$res = $this->roadevent->updateToSendback($eventid);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}


	/**
	 * @desc   '事件信息详情'页面->结束当前事件->更新数据库,保存新状态
	 */
	public function playOffTheInfo(){
		$eventid = $this->input->post('eventid');
		$realovertime = $this->input->post('realovertime');
		$operateId = getsessionempid();
		$operateName = getsessionempname();

		$res = $this->roadevent->updateStatusToOff($eventid,$realovertime,$operateId,$operateName);
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
		$data['eventtype'] = $this->roadevent->selectEventType($eventType);
		//再拿细类
		$data['eventcause'] = $this->roadevent->selectEventCause($eventType);

		//管理处和路段下拉框联动
		$data['roadper'] = $this->roadpoi->selectAllRoadPer();

		$roadold['0'] = $this->roadpoi->selectAllRoad();
		foreach($data['roadper'] as $k => $v){
			$roadold[$v['id']] = $this->roadpoi->selectRoadInPer((string)$v['roadoldids']);
			//var_dump($roadold);exit;
		}

		$roadoldid = array();$roadname = array();
		foreach($roadold as $k=>$v){
			if($v == null){
				$roadoldid[$k] = array();
				$roadname[$k] = array();
			}else{
				foreach($v as $kk=>$vv){
					$roadoldid[$k][$kk] = $vv['roadoldid'];
					$roadname[$k][$kk] = $vv['shortname'];
				}
			}
		}
		$data['roadoldidArr'] = json_encode($roadoldid);
		$data['roadoldnameArr'] = json_encode($roadname);
		//再拿高速公路
		$data['roadSel'] = $this->roadpoi->selectAllRoad();
		//交通状况单选框数据
		$data['roadTrafficColor'] = $this->roadevent->selectRoadTrafficColor();

		$eventid = $this->input->get('eventid');//高德eventid
        $data['gaodedata'] = array();
		if(!empty($eventid)){
			$data['gaodedata'] = $this->roadevent->getGDEventTrafficByEvent($eventid);//获取对应ID的高德信息
			$data['gaodedata']['direction'] = $this->getDirection($data['gaodedata']['direction']);
		}
		$this->load->view('admin/MsgPublish/RoadEvent/NewRoadEventListNew',$data);
	}

	/**
	 * 重置方向
	 */
	public function getDirection($direction){
		$newdirection = 0;
		if($direction==0){
			$newdirection = 1;
		}else if($direction==1){
			$newdirection = 2;
		}
		return $newdirection;
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
		$planovertime = $this->input->post('planovertime');
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

		$res = $this->roadevent->insertNewInfoMsg($eventid,$roadoldid,$occtime,$planovertime,$eventType,$eventCause,$eventCauseName,$direction,$directionName,$TrafficColor,$startStationId,$startStationArr,$endStationId,$endStationArr,$pushInfo,$EmplId,$EmplName,$coor,$trafficSplitcode);
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
		$coor_s = $this->roadevent->selectCoor($startid);
		$coor_e = $this->roadevent->selectCoor($endid);
		$average['x'] = ($coor_s['coor_x'] + $coor_s['coor_x'])/2;
		$average['y'] = ($coor_s['coor_y'] + $coor_s['coor_y'])/2;
		return $average;
	}


	/**
	 * @desc   查询stationcode并拼接
	 * @return [type]               [description]
	 */
	public function getTrafficSplitcode($startid,$endid){
		$trafficSplitcode = $this->roadevent->selectTrafficSplitcode($startid,$endid);
		return $trafficSplitcode;
	}


	public function delEventMsg(){
		$deleteValue = $this->input->post('deleteValue');

		$deleteArr = explode(',',$deleteValue);
		$operateId = getsessionempid();
		$operateName = getsessionempname();

		$res = $this->roadevent->deleteEventMsg($deleteArr,$operateId,$operateName);

		if ($res) {
			ajax_success(true,null);
		}else{
			ajax_success(false,null);
		}
	}

	public function showRealovertime(){
		$data['eventid'] = $this->input->get('eventid');
		$data['eventtype'] = $this->input->get('eventtype');

		$this->load->view('admin/MsgPublish/RoadEvent/realovertimeList',$data);
	}

	/********************管制事件START********************/
	public function operateControlEventMsg(){
		$data['eventId'] = $this->input->get('eventid');
		$data['eventtype'] = $this->input->get('eventtype');
		$data['hasRescue'] = $this->checkRescue($data['eventtype']);
		if ($data['eventId'] === '0') {//新增
			
		}else{
			$data['data'] = $this->roadevent->selectControlEventMsg($data['eventId']);
			/*$data['station'] = $this->roadevent->selectStationById($data['eventId']);*/
			$station = $this->roadevent->selectStationById($data['eventId']);
			$stationStr = '';
			for ($i=0; $i < count($station); $i++) {
				$stationStr .= $station[$i]['stationid'].',';
			}
			$data['station'] = substr($stationStr,0,(count($stationStr)-2));
		}
		// $data['eventtype'] = '1006005';
		$data['road'] = $this->roadpoi->selectAllRoad();
		$data['reason'] = $this->roadevent->selectControlReason();

		$this->load->view('admin/MsgPublish/RoadEvent/OperateControlEventList',$data);
	}

	/**
	 * @desc   新增/修改管制事件->改变路段下拉选择->获取相应的路段站点数据
	 */
	public function getStationMsg(){
		$roadoldid = $this->input->post('roadoldid');
		$data['direction'] = $this->roadevent->selectDirectionByRoad($roadoldid);
		$data['station'] = $this->roadevent->selectStationByRoad_c($roadoldid);

		ajax_success($data,null);
	}

	/**
	 * @desc   '管制事件'->新增/查看->保存新/修改后的事件信息内容
	 * @return [type]      [description]
	 */
	public function saveControlEventMsg(){
		$eventId = $this->input->post('eventId');
		$roadSel = $this->input->post('roadSel');
		$occTime = $this->input->post('occTime');
		$planovertime = $this->input->post('planovertime');
		$realovertime = $this->input->post('realovertime');
		$station = $this->input->post('station');
		$dir1_come = $this->input->post('dir1_come');
		$dir1_out = $this->input->post('dir1_out');
		$dir2_come = $this->input->post('dir2_come');
		$dir2_out = $this->input->post('dir2_out');
		$reasonSel = $this->input->post('reasonSel');
		$pushContent = $this->input->post('pushContent');
		$eventstatus = $this->input->post('eventstatus');
		$eventtype = $this->input->post('eventtype');

		$EmplId = getsessionempid();
		$EmplName = getsessionempname();
		if(isEmpty($EmplId)){
			ajax_error('SESSION已丢失,无法执行当前操作!');return;
		}

		if ($eventId === '0') {//新增
			$eventId = create_guid();//创建新的eventId
			$res = $this->roadevent->saveNewControlEventMsg($eventtype,$eventId,$roadSel,$occTime,$planovertime,$realovertime,$station,$dir1_come,$dir1_out,$dir2_come,$dir2_out,$reasonSel,$pushContent,$EmplId,$EmplName);//,$eventstatus
		}else{
			$res = $this->roadevent->updateControlEventMsg($eventtype,$eventId,$roadSel,$occTime,$planovertime,$realovertime,$station,$dir1_come,$dir1_out,$dir2_come,$dir2_out,$reasonSel,$pushContent,$eventstatus,$EmplId,$EmplName);
		}

		if ($res)
			ajax_success(true,null);
		else
			ajax_error('操作数据库出错!');
	}

	public function finishControlEventMsg(){
		$eventId = $this->input->post('eventId');
		$realovertime = $this->input->post('realovertime');

		$res = $this->roadevent->updateToFinishControlEvent($eventId,$realovertime);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}

	/**
	 * [sendbackControlEvent 打回事件]
	 * @version 2016-04-22 1.0
	 */
	public function sendbackControlEvent(){
		$eventid = $this->input->post('eventId');

		$res = $this->roadevent->updateToSendbackControlEvent($eventId);

		if ($res == true)
			ajax_success(true,null);
		else
			ajax_error($res);
	}
	/********************管制事件END********************/
}